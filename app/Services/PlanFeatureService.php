<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PlanFeature;
use App\Enums\PlanName;
use App\Models\Charge;
use App\Models\Plan;
use App\Models\Shop;
use App\Exceptions\FeatureNotAllowedException;
use Illuminate\Support\Arr;

class PlanFeatureService
{
    /**
     * @param PlanFeature $feature
     * @param Shop|null $shop
     * @return bool
     */
    public function hasEnabled(PlanFeature $feature, ?Shop $shop = null): bool
    {
        $shop = $this->getShop($shop);

        if(is_null($shop)){
            return false;
        }

        if ($shop->isGrandfathered()) {
            return true;
        }

        $plan = $shop->plan;

        if(!$plan){
            return false;
        }

        return Arr::get($plan->meta, "ability.{$feature->value}", false);
    }


    /**
     * @param PlanFeature $feature
     * @param Shop|null $shop
     * @return bool
     */
    public function hasDisabled(PlanFeature $feature, ?Shop $shop = null): bool
    {
        return !$this->hasEnabled($feature, $shop);
    }


    /**
     * @param PlanFeature $feature
     * @param Shop|null $shop
     * @return void
     * @throws FeatureNotAllowedException
     */
    public function allows(PlanFeature $feature, ?Shop $shop = null): void
    {
        if (!$this->hasEnabled($feature, $shop)) {
            throw new FeatureNotAllowedException($feature->value);
        }
    }


    /**
     * @param PlanFeature $feature
     * @param Shop|null $shop
     * @return mixed
     */
    public function getFlagValue(PlanFeature $feature, ?Shop $shop = null): mixed
    {
        $shop = $this->getShop($shop);

        if (is_null($shop)) {
            return null;
        }

        $plan = $this->getPlan($shop);

        if (is_null($plan)) {
            return null;
        }

        if ($this->isTrialActive($plan, $shop)) {
            $plan = $this->getFreePlan();
        }

        return Arr::get($plan->meta, "ability.{$feature->value}", 0);
    }

    /**
     * @param Shop|null $shop
     * @return Shop|null
     */
    private function getShop(?Shop $shop): ?Shop
    {
        if (is_null($shop) && !auth()->check()) {
            return null;
        }

        if (is_null($shop)) {
            $shop = shop();
        }

        return $shop;
    }

    /**
     * @param Shop $shop
     * @return Plan|null
     */
    private function getPlan(Shop $shop): ?Plan
    {
        return Plan::find($shop->plan_id ?? 1);
    }

    /**
     * @param Plan $plan
     * @param Shop $shop
     * @return bool
     */
    private function isTrialActive(Plan $plan, Shop $shop): bool
    {
        $charge = Charge::where('shop_id', $shop->id)->orderBy('created_at', 'desc')->first();

        return $plan->name !== PlanName::FREE->value && $charge && now()->lt(carbon($charge->trial_ends_on));
    }

    /**
     * @return Plan
     */
    private function getFreePlan(): Plan
    {
        return Plan::where('name', PlanName::FREE->value)->first();
    }

}
