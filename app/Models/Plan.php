<?php

namespace App\Models;

use App\Enums\PlanStatus;
use Osiset\ShopifyApp\Storage\Models\Plan as IPlan;

class Plan extends IPlan
{
    protected $appends = [
        'action_url',
        'is_active',
        'is_max_plan',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->casts['meta'] = 'json';
    }

    /**
     * @return string|null
     */
    public function getActionUrlAttribute(): string|null
    {
        $shop = shop();

        if ($shop && $this->id !== $shop->plan_id) {
            return route('subscription.create', ['plan' => $this->id]);
        }

        return null;
    }


    public function getIsActiveAttribute(): bool
    {
        return $this->id === shop()?->plan_id;
    }

    public function getIsMaxPlanAttribute(): bool
    {
        // TODO: Have to improve this query ===================
        $plans = Plan::where('status', PlanStatus::ACTIVE->value)->orderBy('price', 'ASC')->get();

        $lastPlan = $plans->last();

        if ($this->id === $lastPlan->id) {
            return true;
        }

        return false;
    }

    public function isTest(): bool
    {
        return shop()->development_store ?: parent::isTest();
    }
}
