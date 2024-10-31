<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppResource;
use App\Services\AppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Js;

class AppController extends Controller
{
    protected AppService $appService;

    public function __construct(AppService $appService)
    {
        $this->appService = $appService;
    }

    public function index(Request $request): JsonResponse
    {

        $apps = $this->appService->getApps($request->all());
        $appsCollection = AppResource::collection($apps);
        return api(['apps' => $appsCollection])->success('List of Apps retrieved successfully');
    }

    public function show($uid): JsonResponse
    {
        $app = $this->appService->getAppByUid($uid);
        $appResource = new AppResource($app);
        return api(['app' => $appResource])->success('App retrieved successfully');
    }

}
