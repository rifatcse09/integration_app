<?php

namespace App\Services;

use App\Enums\MetaFieldKey;
use App\Models\Shop;
use Illuminate\Support\Facades\Crypt;

class MetaFieldService extends BaseService
{
    private array $metafields = [];

    private string $metaFieldNamespace = "bitintegrations";

    public function __construct(protected ShopifyService $shopifyService)
    {
        //
    }


    /**
     * Update an existing metafield or create a new one for a shop.
     *
     * @param Shop $shop The shop entity
     * @param MetaFieldKey $metaFieldKey The key of the metafield
     * @return void
     */
    public function updateOrCreateMetaField(Shop $shop, MetaFieldKey $metaFieldKey): void
    {
        $this->loadMetafields($shop);

        $metafieldData = $this->getMetafieldData($shop, $metaFieldKey);
        $existingId = $this->checkMetaFieldExists($shop, $metaFieldKey);
        if ($existingId) {
            $this->updateMetafield($shop, $existingId, $metafieldData);
        } else {
            $this->createMetafield($shop, $metafieldData);
        }
    }

    /**
     * Clear the value of specified metafields for a shop and update them with a new message.
     *
     * @param Shop $shop The shop entity
     * @param MetaFieldKey[] $metaFieldKeys An array of MetaFieldKey enums
     * @param string $message The message to set in the metafield value
     * @return void
     */
    public function clearMetaFieldValue(Shop $shop, array $metaFieldKeys, string $message): void
    {
        $this->loadMetafields($shop);

        foreach ($metaFieldKeys as $key) {
            // Validate that each element is an instance of MetaFieldKey
            // Skip processing if the key is not a MetaFieldKey instance or if the key is STOREFRONT_ACCESS_TOKEN
            if (!$key instanceof MetaFieldKey || $key == MetaFieldKey::STOREFRONT_ACCESS_TOKEN) {
                continue;
            }

            // Initialize the value to an empty array
            $value = [];

            $metafieldData = $this->prepareMetafieldData($key, json_encode($value), $shop->uid);

            $existingId = $this->checkMetaFieldExists($shop, $key);
            if ($existingId) {
                $this->updateMetafield($shop, $existingId, $metafieldData);
            }
        }
    }

    /**
     * @param Shop $shop
     * @return void
     */
    public function deleteMetafields(Shop $shop): void
    {
        $metafields = $this->shopifyService->getMetaFieldList($shop);

        foreach ($metafields as $metafield) {
            if ($metafield['namespace'] === $this->metaFieldNamespace) {
                $shop->api()->rest("DEL", admin_api() . "/metafields/" . $metafield['id'] . ".json");
            }
        }
    }

    /**
     * Retrieve the data for the metafield.
     *
     * @param Shop $shop The shop entity
     * @param MetaFieldKey $metaFieldKey The key of the metafield
     * @return array The metafield data
     */
    private function getMetafieldData(Shop $shop, MetaFieldKey $metaFieldKey): array
    {
        switch ($metaFieldKey) {
            case MetaFieldKey::STOREFRONT_ACCESS_TOKEN:
                return $this->getStorefrontAccessTokenMetafield($shop);
            default:
                return [];
        }
    }

    /**
     * Create a new metafield for the shop.
     *
     * @param Shop $shop The shop entity
     * @param array $metafieldData The data to create the metafield with
     * @return void
     */
    private function createMetafield(Shop $shop, array $attributes): void
    {
        $shop->api()->rest("POST", admin_api() . "/metafields.json", [
            "metafield" => $attributes
        ]);
    }


    /**
     * Update an existing metafield for the shop.
     *
     * @param Shop $shop The shop entity
     * @param int $metafieldId The ID of the existing metafield
     * @param array $metafieldData The data to update the metafield with
     * @return void
     */
    private function updateMetafield(Shop $shop, int|string $metafieldId, array $attributes): void
    {
        $shop->api()->rest("PUT", admin_api() . "/metafields/" . $metafieldId . ".json", [
            "metafield" => $attributes
        ]);
    }

    private function getStorefrontAccessTokenMetafield(Shop $shop): array
    {
        $value = Crypt::encryptString($shop->name);
        return $this->prepareMetafieldData(MetaFieldKey::STOREFRONT_ACCESS_TOKEN, $value, $shop->uid, 'single_line_text_field');
    }

    private function prepareMetafieldData(MetaFieldKey $key, string $value, string $wonerId, string $type = "json"): array
    {
        return [
            "namespace" => $this->metaFieldNamespace,
            "key" => $key->value,
            "value" => $value,
            "type" => $type,
            "ownerId" => "gid://shopify/Shop/{$wonerId}",
        ];
    }


    /**
     * Load metafields for the shop and store them in the property.
     *
     * @param Shop $shop The shop entity
     * @return void
     */
    private function loadMetafields(Shop $shop): void
    {
        $this->metafields = $this->shopifyService->getMetaFieldList($shop);
    }

    /**
     * Check if the metafield already exists for the shop.
     *
     * @param Shop $shop The shop entity
     * @param MetaFieldKey $metaFieldKey The key of the metafield
     * @return int|null The ID of the existing metafield or null if it doesn't exist
     */
    private function checkMetaFieldExists(Shop $shop, MetaFieldKey $metaFieldKey): ?int
    {

        foreach ($this->metafields as $metafield) {
            if ($metafield['namespace'] === $this->metaFieldNamespace && $metafield['key'] === $metaFieldKey->value) {
                return $metafield['id'];
            }
        }

        return null;
    }
}
