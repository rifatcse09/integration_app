<?php

namespace App\Http\Controllers;

use App\Services\PlanService;
use Illuminate\Http\Request;

class PlanController extends Controller
{

    public function __construct(protected PlanService $planService)
    {

    }

    public function index()
    {
        return api(['plans' => $this->planService->list()])->success('Plans list fetched successfully!');
    }

    public function currentPlan()
    {
        $shop = shop();
        return api([
            'current_plan' => $this->planService->currentPlan($shop),
        ])
            ->success('Current plan fetched successfully!');
    }
}
