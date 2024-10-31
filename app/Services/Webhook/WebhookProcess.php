<?php

namespace App\Services\Webhook;

use App\Enums\Status;
use App\Enums\WebhookType;
use App\Jobs\ProcessIntegrationJob;
use App\Models\App;
use App\Models\CustomWebhook;
use App\Models\Integration;
use App\Models\Shop;
use App\Models\WebhookEvent;
use App\Models\WebhookRequest;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class WebhookProcess extends BaseService
{
    public function storeRequest(Request $request, int $provider): ?WebhookRequest
    {

        $webhook = WebhookRequest::where('request_id', $request->header('X-Shopify-Webhook-Id'))->first();

        $topic = $request->header('X-Shopify-Topic');

        $webhookTopicsData = config('integration.services.shopify.topics_data', []);

        if (!isset($webhookTopicsData[$topic])) {
            return null;
        }


        $shopDomain = $request->header('x-shopify-shop-domain');
        $shopId = Shop::where('name', $shopDomain)
            ->pluck('id')
            ->first();

        if (!$webhook) {
            $webhook = new WebhookRequest();
            $webhook->fill([
                'shop_id' => $shopId,
                'request_id' => $request->header('X-Shopify-Webhook-Id'),
                'provider' => $provider,
                'topic' => $request->header('X-Shopify-Topic'),
                'payload' => $request->all(),
                'headers' => $request->headers->all(),
                'status' => Status::ACTIVE->value,
                'payload_hash' => '',
            ]);

            $webhook->save();

            return $webhook;
        }

        return null;
    }

    public function process(int $webhookId): bool
    {
        $webhookRequest = WebhookRequest::find($webhookId);
        $topic = $webhookRequest['topic'];
        $shopDomain = $webhookRequest['headers']['x-shopify-shop-domain'][0];

        if ($this->getSpecialTopic($webhookRequest)) {
            $specialShopifyWebhookJob = $this->getSpecialTopic($webhookRequest);
            $payloadData = json_decode(json_encode($webhookRequest['payload']));
            dispatch(new $specialShopifyWebhookJob($shopDomain, $payloadData));
            return true;
        }

        $webhookEventId = WebhookEvent::where('topic', $topic)
            ->where('type', WebhookType::SHOPIFY->value)
            ->pluck('id')
            ->first();


        $shopId = Shop::where('name', $shopDomain)
            ->pluck('id')
            ->first();

        $integrations = $this->getActiveIntegrations($shopId, $webhookEventId);

        if ($integrations->isEmpty()) {
            return false;
        }

        $this->dispatchIntegrationJobs($integrations, $webhookRequest['id']);
        return true;
    }

    public function customProcess(Request $request, string $uniqueCodeWithShop): array
    {
        $shopId = $this->decodeShopAndCode($uniqueCodeWithShop)[0];

        $customWebhook = CustomWebhook::with('webhookEvent')->where('unique_code', $uniqueCodeWithShop)->firstOrFail();
        $customWebhookId = Arr::get($customWebhook, 'id');

        // check event generated
        if (!$customWebhook->webhookEvent) {
            $this->createWebhookEvent($request->all(), $customWebhookId);
            return ['request' => $request->all()];

        }

        $this->processWebhookRequest($request, $shopId, $uniqueCodeWithShop, $customWebhookId, $customWebhook->webhookEvent->id);
        return ['request' => $request->all()];
    }

    private function createWebhookEvent(array $payload, int $customWebhookId): WebhookEvent
    {
        $app = App::where('pointer', 'webhook')->firstOrFail();
        $webhookEvent = new WebhookEvent();
        $webhookEvent->fill([
            'app_id' => $app->id,
            'type' => WebhookType::CUSTOM->value,
            'custom_webhook_id' => $customWebhookId,
            'payload' => $payload,
        ]);
        $webhookEvent->save();

        return $webhookEvent;
    }

    private function processWebhookRequest(Request $request, int $shopId, string $uniqueCodeWithShop, int $customWebhookId, int $webhookEventId): bool
    {
        // Create and save the webhook request
        $webhook = new WebhookRequest();
        $webhook->fill([
            'shop_id' => $shopId,
            'request_id' => $uniqueCodeWithShop,
            'custom_webhook_id' => $customWebhookId,
            'provider' => WebhookType::CUSTOM->value,
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
            'status' => Status::ACTIVE->value,
            'payload_hash' => '',
        ]);
        $webhook->save();

        // Fetch active integrations
        $integrations = $this->getActiveIntegrations($shopId, $webhookEventId);
        if ($integrations->isEmpty()) {
            return false;
        }

        // Dispatch integration jobs
        $this->dispatchIntegrationJobs($integrations, $webhook->id);
        return true;
    }

    public function getSpecialTopic(WebhookRequest $webhookRequest): ?string
    {
        $topic = $webhookRequest['topic'];
        $topics = config('integration.services.shopify.special_handlers', []);

        return $topics[$topic] ?? null;
    }

    public function createWebhookUrl(): string
    {
        $uniqueId = uniqid();
        $encodedKey = $this->encodeShopAndCode(shop()->id, $uniqueId);

        $customWebhook = new CustomWebhook();
        $customWebhook->unique_code = $encodedKey;
        $customWebhook->shop_id = shop()->id;
        $customWebhook->save();
        return route('handle.webhook', ['id' => $encodedKey]);
    }

    public function encodeShopAndCode(int|string $shop_id, string $unique_code): string
    {
        $concatValue = $shop_id . ':' . $unique_code;
        return base64_encode($concatValue); // Encodes the concatenated string
    }

    public function decodeShopAndCode(string $encodedValue): array
    {
        $decodedValue = base64_decode($encodedValue);
        return explode(':', $decodedValue); // Returns [shop_id, unique_code]
    }

    /**
     * Get active integrations for a specific shop and webhook event.
     *
     * @param int $shopId
     * @param int $webhookEventId
     * @return Collection
     */
    private function getActiveIntegrations(int $shopId, int $webhookEventId): Collection
    {
        return Integration::with('app')
            ->where('shop_id', $shopId)
            ->where('event_id', $webhookEventId)
            ->where('status', Status::ACTIVE->value)
            ->get();
    }

    private function dispatchIntegrationJobs(Collection $integrations, int $webhookRequestId): void
    {
        foreach ($integrations as $integration) {
            Log::info('Integration', ['integration' => $integration->app]);
            ProcessIntegrationJob::dispatch(
                integrationId: $integration->id,
                appPointer: $integration->app->pointer,
                webhookRequestId: $webhookRequestId
            );
        }
    }

}
