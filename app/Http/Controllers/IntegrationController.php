<?php

namespace App\Http\Controllers;

use App\Http\Resources\IntegrationDetailResource;
use App\Http\Resources\IntegrationResource;
use App\Models\App;
use App\Services\ActivityLogService;
use App\Services\AppService;
use App\Services\CredentialService;
use App\Services\Factory\ServiceFactory;
use App\Services\IntegrationService;
use App\Services\WebhookEventService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class IntegrationController extends Controller
{

    public function __construct(
        protected ServiceFactory     $serviceFactory,
        protected AppService         $appService,
        protected IntegrationService $integrationService,
        protected ActivityLogService $activityLogService
    )
    {
    }

    /**
     * Generates a token based on the provided UIDs and returns it in an API response.
     *
     * @param string $triggerUid
     * @param string $credentialUid
     * @param string|null $eventUid UID of the event, can be null.
     * @return JsonResponse
     * @throws Exception if required UIDs are missing.
     */
    public function createIntegrationToken(
        string  $triggerUid,
        string  $credentialUid,
        ?string $eventUid
    ): JsonResponse
    {
        if (empty($triggerUid)) {
            throw new \Exception('Trigger UID is required');
        }

        if (empty($credentialUid)) {
            throw new \Exception('Credential UID is required');
        }

        $base64EncodedData = encryptAndEncode([
            'credential_uid' => $credentialUid,
            'shop_id' => shop()->id,
            'event_uid' => $eventUid ?? null,
            'trigger_uid' => $triggerUid
        ]);
        return api(['token' => $base64EncodedData])
            ->success('Token');
    }

    /**
     * Initialize service data based on a decrypted token.
     *
     * This method decrypts the provided token to extract state information,
     * retrieves the corresponding credential using the credential UID and shop ID,
     * and then uses the appropriate service to fetch initial data.
     *
     * @param string $token The encrypted token containing the state information.
     * @return JsonResponse Returns a JSON response with the initial service data and a success message.
     * @throws BindingResolutionException
     */
    public function initializeServiceData(string $token): JsonResponse
    {
        $decryptedState = decodeAndDecrypt($token);
        $credentialUid = Arr::get($decryptedState, 'credential_uid');

        $credential = app()->make(CredentialService::class)->getCredentialByUidShopId($credentialUid, shop()->id);
        $initialData = ServiceFactory::getService($credential->app->pointer)->getInitialData($decryptedState);

        return api(['initial_data' => $initialData])
            ->success('Field List');

    }

    /**
     * Handle the incoming webhook payload request.
     *
     * This method retrieves the event associated with the provided event UID,
     * then uses the appropriate service to fetch the payload data for that event.
     *
     * @param Request $request The incoming HTTP request containing the event UID.
     * @return JsonResponse Returns a JSON response with the payload data and a success message.
     * @throws Exception
     */
    public function webhookPayload(Request $request): JsonResponse
    {
        $payload = [];

        $eventUid = Arr::get($request, 'event_uid');
        if ($eventUid) {
            $webhookEventService = app(WebhookEventService::class);
            $event = $webhookEventService->getEventsByUid($eventUid);

            if ($event) {
                $payload = $webhookEventService->getPayloadByEventId($eventUid, $event->app->pointer);
            }
        }

        return api(['payload' => $payload])
            ->success('Field List');
    }

    /**
     * Dynamic method load if exist in action
     *
     * @throws Exception
     */
    public function handleAction(App $app, string $action, Request $request): JsonResponse
    {
        $service = ServiceFactory::getService($app->pointer);
        if (!method_exists($service, $action)) {
            throw new \Exception('Service or action not found');
        }
        $data = $service->$action($request->all());

        return api($data)
            ->success('Data Fetched successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->integrationService->store($request->all());
        return api(['res' => $response])
            ->success('Filed map stored');
    }

    public function index(Request $request): JsonResponse
    {
        $integrations = $this->integrationService->index($request);
        $integrationsResource = new IntegrationResource($integrations);
        return api($integrationsResource)
            ->success('Integration List');
    }

    public function detail(string $integrationUid): JsonResponse
    {
        $integration = $this->integrationService->getIntegrationDetails($integrationUid);
        $integrationsResource = new IntegrationDetailResource($integration);
        return api(['integration' => $integrationsResource])
            ->success('Integration Details Successfully');
    }

}
