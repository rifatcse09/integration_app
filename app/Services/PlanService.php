<?php

namespace App\Services;

use App\Enums\PlanFeature;
use App\Enums\PlanStatus;
use App\Enums\Popup\PopupLogType;
use App\Exceptions\LimitExceededException;
use App\Jobs\MetaFieldClearJob;
use App\Models\Plan;
use App\Models\PopupLog;
use App\Models\Shop;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PlanService extends BaseService
{
    public function list()
    {
        return Plan::orderBy('price', 'asc')
            ->get()
            ->toArray();
    }

    public function currentPlan(Shop $shop): array|null
    {
        $plan = $shop->plan;
        if ($plan) {
            $plan = $plan->toArray();
            // $plan['meta']['ability'] = array_replace_recursive(config('popup.ability'), $plan['meta']['ability']);
        }

        return $plan;
    }


    public function defaultPlan(): Plan|null
    {
        return Plan::where([
            'on_install' => true,
            'status' => PlanStatus::ACTIVE->value
        ])
            ->first();
    }
}
