<?php

namespace App\Services;

use App\Contacts\IntegrationContractService;
use App\Models\Shop;
use App\Models\WebhookEvent;
use Illuminate\Support\Arr;

class ShopifyService
{
    public function getShopFromApi(Shop $shop): array
    {
        $shopDetails = [];

        $query = <<<GRAPHQL
            query {
                shop {
                    id
                    name
                    email
                    myshopifyDomain
                    primaryDomain {
                        host
                    }
                    plan {
                        partnerDevelopment
                    }
                }
            }
        GRAPHQL;

        $shopResponse = $shop->api()->graph($query);

        if (!$shopResponse['errors']) {
            $shop = $shopResponse['body']['data']['shop']->toArray();

            $shopDetails = [
                'shopify_id' => Arr::last(explode("/", Arr::get($shop, 'id'))),
                'title' => Arr::get($shop, 'name'),
                'name' => Arr::get($shop, 'myshopifyDomain'),
                'email' => Arr::get($shop, 'email'),
                'primary_domain' => Arr::get($shop, 'primaryDomain.host'),
                'development_store' => Arr::get($shop, 'plan.partnerDevelopment'),
            ];
        }

        return $shopDetails;
    }
}
