<?php

namespace App\Integrations\ActiveCampaign;

use App\Enums\AppType;
use App\Enums\WebhookType;
use App\Http\Resources\WebhookEventResource;
use App\Services\AppService;
use App\Services\CredentialService;
use App\Services\WebhookEventService;
use Illuminate\Support\Arr;

class ActiveCampaignIntegration
{
    public function __construct(
        protected CredentialService $credentialService,
        protected AppService $appService,
        protected ActiveCampaignWebhookProcess $activeCampaignWebhookProcess,
        protected ActiveCampaignClient $activeCampaignClient,
        protected WebhookEventService $webhookEventService)
    {
    }

    public function getCampaigns(array $request): array
    {
        $requestParams = [
            'limit' => 1000,
        ];
        $apiConfig = $this->activeCampaignClient->prepareApiRequestWithMethod($request, 'lists');
        $response = http_get($apiConfig['apiEndpoint'], $apiConfig['headers'], $requestParams);
        $lists = [];
        $allLists = $response['lists'];
        foreach ($allLists as $list) {
            $lists[] = [
                'name' => $list['id'],
                'label' => $list['name'],
            ];
        }
        return ['campaignList' => $lists];
    }

    public function getAccounts(array $request): array
    {
        $apiConfig = $this->activeCampaignClient->prepareApiRequestWithMethod($request, 'accounts');
        $response = http_get($apiConfig['apiEndpoint'], $apiConfig['headers']);

        $lists = [];
        $accounts = Arr::get($response, 'accounts');
        if ($accounts) {
            foreach ($accounts as $account) {
                $lists[] = [
                    'name' => $account['id'],
                    'label' => $account['name'],
                ];
            }
        }
        return ['accountList' => $lists];
    }

    public function getTags(array $request): array
    {
        $apiConfig = $this->activeCampaignClient->prepareApiRequestWithMethod($request, 'tags');
        $response = http_get($apiConfig['apiEndpoint'], $apiConfig['headers']);
        $lists = [];
        $tags = $response['tags'];
        foreach ($tags as $tag) {
            $lists[] = [
                'name' => $tag['id'],
                'label' => $tag['tag'],
            ];
        }
        return ['tagList' => $lists];
    }

    public function getFields(): array
    {
        $options = [
            [
                "value" => "email",
                "label" => "Email",
            ],
            [
                "value" => "firstName",
                "label" => "First Name",
            ],
            [
                "value" => "lastName",
                "label" => "Last Name",
            ],
            [
                "value" => "phone",
                "label" => "Phone",
            ],
        ];
        $fields = [
            [
                "value" => "email",
                "label" => "Email",
                "required" => true,
                "options" => $options
            ],
            [
                "value" => "firstName",
                "label" => "First Name",
                "required" => false,
                "options" => $options
            ],
            [
                "value" => "lastName",
                "label" => "Last Name",
                "required" => false,
                "options" => $options
            ],
            [
                "value" => "phone",
                "label" => "Phone",
                "required" => false,
                "options" => $options
            ]
        ];
        return ['fields' => $fields];
    }

    public function getInitialData(array $decryptedState): array
    {
        $credentialUid = Arr::get($decryptedState, 'credential_uid');
        $triggerUid = Arr::get($decryptedState, 'trigger_uid');
        $eventUid = Arr::get($decryptedState, 'event_uid');

        $app = $this->appService->getAppByUid($triggerUid);
        $modules = $this->getCampaigns($decryptedState);

        $accounts = $this->getAccounts($decryptedState);
        $tags = $this->getTags($decryptedState);

        $webhookEvents = $app->type == WebhookType::SHOPIFY->value ? WebhookEventResource::collection($this->webhookEventService->getEventsByAppId($app->id)) : [];

        $credential = $this->credentialService->getCredentialByUidShopId($credentialUid, shop()->id);
        $actionUid = $credential->app->uid;
        $actionInfo = $this->appService->getAppByUid($actionUid);

        $payload = $this->webhookEventService->getPayloadByEventId($eventUid, $app->pointer);

        $service = $credential->app->pointer;

        return [
            'trigger_name' => $app->pointer,
            'service' => $service,
            'modules' => $modules['campaignList'],
            'account_list' => $accounts['accountList'],
            'tag_list' => $tags['tagList'],
            'event_list' => $webhookEvents,
            'action_uid' => $actionUid,
            'credential_uid' => $credentialUid,
            'trigger_uid' => $triggerUid,
            'event_uid' => $eventUid,
            'event_payload' => $payload,
            'trigger_logo_url' => $app->logo_url,
            'action_logo_url' => $actionInfo->logo_url,
        ];
    }

    public function processWebhook(?int $webhookRequestId, int $integrationId, bool $integrationTest = true): void
    {
        $this->activeCampaignWebhookProcess->processWebhook($webhookRequestId, $integrationId, $integrationTest);
    }
}
