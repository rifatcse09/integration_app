<?php

namespace App\Http\Controllers;

use App\Enums\WebhookType;
use App\Http\Resources\WebhookEventResource;
use App\Models\App;
use App\Models\CustomWebhook;
use App\Services\Webhook\WebhookProcess;
use App\Services\WebhookEventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class WebhookController extends Controller
{

    public function __construct(private readonly WebhookProcess $webhookProcess)
    {
    }

    public function handle(Request $request): JsonResponse
    {
        $webhook = $this->webhookProcess->storeRequest($request, WebhookType::SHOPIFY->value);
        if ($webhook) {
            $this->webhookProcess->process($webhook->id);
        }

        return api(['webhook' => $webhook])
            ->success('Webhook Events List');
    }

    public function handleCustom(Request $request, string $uniqueCode): JsonResponse
    {
        $webhook = $this->webhookProcess->customProcess($request, $uniqueCode);

        return api(['webhook' => $webhook])
            ->success('Webhook Event');
    }

    public function getEventsByAppUid(WebhookEventService $webhookEventService, App $app): JsonResponse
    {

        $webhookEvents = WebhookEventResource::collection($webhookEventService->getEventsByAppId($app->id));

        return api(['webhook_events' => $webhookEvents, 'trigger' => $app->pointer])
            ->success('Webhook Events List');
    }

    public function createWebhookUrl(): JsonResponse
    {
        $webhookUrl = $this->webhookProcess->createWebhookUrl();
        return api(['webhookURL' => $webhookUrl])
            ->success('Custom Webhook URL');
    }

    public function getEventsByUniqueCode(WebhookEventService $webhookEventService, App $app, string $uniqueCode): JsonResponse
    {
        if(!$uniqueCode) {
           throw new BadRequestHttpException("Unique code not found");
        }
        $webhookEvents = new WebhookEventResource($webhookEventService->getEventsByUniqueId($app->id, $uniqueCode));

        return api(['webhook_events' => [$webhookEvents]])
            ->success('Webhook Events List by Unique ID');

    }


}
