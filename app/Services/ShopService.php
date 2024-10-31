<?php

namespace App\Services;

use App\Models\Shop;
use Osiset\ShopifyApp\Contracts\Objects\Values\ShopDomain;
use Osiset\ShopifyApp\Storage\Scopes\Namespacing;

class ShopService extends BaseService
{
    public function __construct(protected ShopifyService $shopifyService, protected PlanService $planService)
    {
        //
    }

    public function retrieveShopByName(string|ShopDomain $shopDomain): Shop
    {
        return Shop::withoutGlobalScope(Namespacing::class)
            ->withTrashed()
            ->where('name', $shopDomain)
            ->firstOrFail();
    }

    public function resetShopData(Shop $shop): void
    {
        $plan = $this->planService->defaultPlan();
        $shop->app_setup_completed = false;
        $shop->password = '';
        $shop->plan_id = $plan?->id ?? null;
        $shop->save();
    }

    public function getShopDetails(Shop $shop)
    {
        return [
            "shop" => $this->shopifyService->getShopFromApi($shop),
            // "current_theme" => $this->shopifyService->getCurrentTheme($shop) // currently not using in frontend
        ];
    }

}
