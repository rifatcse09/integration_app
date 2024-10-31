<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityLogResource;
use App\Http\Resources\ActivityLogWithIntegrationResource;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{

    public function __construct(protected ActivityLogService $activityLogService) {

    }
    public function index(Request $request): JsonResponse
    {
        $activities = $this->activityLogService->index($request);
        $activitiesResource = new ActivityLogWithIntegrationResource($activities);
        return api($activitiesResource)
            ->success('Activities List');
    }

    public function activitiesByIntegrationUid(Request $request, $integrationUid): JsonResponse
    {
        $activities = $this->activityLogService->activitiesByIntegrationUid($request, $integrationUid);
        $activitiesResource = new ActivityLogResource($activities);
        return api($activitiesResource)
            ->success('Activities List');
    }
}
