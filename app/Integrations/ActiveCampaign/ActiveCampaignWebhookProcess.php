<?php

namespace App\Integrations\ActiveCampaign;

use App\Enums\LogStatus;
use App\Enums\LogTitle;
use App\Exceptions\ConditionMismatchException;
use App\Exceptions\CustomException;
use App\Models\Credential;
use App\Models\Integration;
use App\Services\ActivityLogService;
use App\Services\IntegrationService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ActiveCampaignWebhookProcess
{
    private bool $logMissingStatus = false;
    private string $logTitle = LogTitle::NOT_SET->value;

    public function __construct(
        protected IntegrationService   $integrationService,
        protected ActiveCampaignClient $activeCampaignClient,
        protected ActivityLogService   $activityLogService)
    {
    }

    public function processWebhook(
        ?int $webhookRequestId,
        int  $integrationId,
        bool $integrationTest = false): void
    {
        $integration = $this->getIntegration($integrationId);

        $integrationPayload = $integration->payload;
        $webhookPayload = $this->integrationService->getWebhookPayload($integration, $webhookRequestId, $integrationTest);

        try {

            $this->checkCondition($integrationPayload, $webhookPayload);

            $fieldData = $this->prepareFieldData($integrationPayload, $webhookPayload);
            $credential = $integration->actionCredential;

            $existContactResponse = $this->existContact($fieldData['contact']['email'], $credential);
            $existContact = Arr::get($existContactResponse, 'contacts');

            $recordApiResponse = $this->handleContact(
                $integrationPayload,
                $existContact,
                $fieldData,
                $credential,
                $integrationPayload['list'],
                $integrationPayload['tags'] ?? []
            );
            $logStatus = $this->logMissingStatus ? LogStatus::MISSING : LogStatus::SUCCESS;

            $this->activityLogService->logActivity($integration, $webhookPayload, $recordApiResponse, $this->logTitle, $logStatus);


        } catch (\Throwable $e) {
            $this->activityLogService->logError($integration, $webhookPayload, $e);
        }
    }

    private function getIntegration(
        int $integrationId): ?Integration
    {
        $integration = Integration::with('app', 'actionCredential')->find($integrationId);
        if (!$integration) {
            throw new CustomException('Integration not found');
        }
        return $integration;
    }

    private function checkCondition(
        array $integrationPayload,
        array $webhookPayload): void
    {
        if ($integrationPayload['condition_status'] && Arr::has($integrationPayload, 'condition')) {
            $conditionCheckStatus = $this->integrationService->conditionLogic($integrationPayload['condition'], $webhookPayload);
            if (!$conditionCheckStatus) {
                throw new ConditionMismatchException('Condition mismatch');
            }
        }
    }

    private function handleContact(
        array        $integrationPayload,
        array        $existContact,
        array        $fieldData,
        array|object $credential,
        string|int   $listId,
        array        $tags): array
    {
        $updateContact = Arr::get($integrationPayload, 'update');
        $recordApiResponse = null;
        $this->logTitle = LogTitle::NOT_SET->value;

        if (!$updateContact && empty($existContact)) {
            $recordApiResponse = $this->storeOrModifyRecord(credential: $credential, method: 'contacts', data: $fieldData);
            $this->logTitle = LogTitle::MEMBER_ADDED->value;;
            $this->processNewContact($recordApiResponse, $listId, $credential, $tags, $integrationPayload);
        } elseif ($updateContact && !empty($existContact)) {
            $recordApiResponse = $this->updateRecord($existContact[0]['id'], $fieldData, $credential);
            $this->logTitle = LogTitle::MEMBER_UPDATED->value;
            $this->processExistingContact($recordApiResponse, $listId, $credential, $tags, $integrationPayload);
        } elseif ($updateContact && empty($existContact)) {
            $recordApiResponse = $this->storeOrModifyRecord(credential: $credential, method: 'contacts', data: $fieldData);
            if (Arr::has($recordApiResponse, 'contact')) {
                $this->logTitle = LogTitle::MEMBER_ADDED->value;
                $this->processNewContact($recordApiResponse, $listId, $credential, $tags, $integrationPayload);
            }
        }

        return $recordApiResponse ?? [];
    }

    private function prepareFieldData(
        array $integrationPayload,
        array $webhookPayload): array
    {
        $fieldData = [];
        $mergeFields = [];

        foreach ($integrationPayload['map'] as $index => $map) {
            $concatenatedData = $this->integrationService->getConcatenatedField($map['value'], $webhookPayload);
            $concatenatedString = $concatenatedData['field'];
            // If any iteration sets logStatus to true, the final logMissingStatus will be true
            $this->logMissingStatus = $this->logMissingStatus || $concatenatedData['logStatus'];

            $actionField = $map['name'];

            if ($actionField === 'email') {
                if (!$concatenatedString) {
                    throw new CustomException('Mapping Email not found');
                }
            }
            $mergeFields[$actionField] = $concatenatedString;

        }

        $fieldData['contact'] = $mergeFields;

        return $fieldData;
    }

    private function processNewContact(
        array        $recordApiResponse,
        string|int   $listId,
        array|object $credential,
        array|object $tags,
        array        $integrationPayload): void
    {
        if (isset($recordApiResponse['contact'])) {
            $contactId = $recordApiResponse['contact']['id'];
            $this->addContactList($listId, $credential, $contactId);
            $this->addTags($tags, $credential, $contactId);
            $this->accountContacts($integrationPayload, $credential, $contactId);
        }
    }

    private function processExistingContact(
        array        $recordApiResponse,
        string|int   $listId,
        array|object $credential,
        array        $tags,
        array        $integrationPayload): void
    {
        $contactId = $recordApiResponse['contact']['id'];
        $this->addContactList($listId, $credential, $contactId);
        $this->addTags($tags, $credential, $contactId);
        $this->accountContacts($integrationPayload, $credential, $contactId);
    }

    public function existContact(
        string           $contactEmail,
        array|Credential $credential): ?array
    {
        $method = "contacts";
        $requestParams = [
            'email' => $contactEmail,
        ];

        $apiConfig = $this->activeCampaignClient->prepareApiRequestWithMethod($credential, $method);
        return http_get($apiConfig['apiEndpoint'], $apiConfig['headers'], $requestParams);
    }

    private function addTags(
        array|object $tags,
        array|object $credential,
        string|int   $contactId): void
    {
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $data['contactTag'] = [
                    'contact' => $contactId,
                    'tag' => $tag
                ];
                $this->storeOrModifyRecord(credential: $credential, method: 'contactTags', data: $data);
            }
        }
    }

    private function addContactList(
        string|int   $listId,
        array|object $credential,
        string|int   $contactId): void
    {
        if (!empty($listId)) {
            $data['contactList'] = [
                'list' => $listId,
                'contact' => $contactId,
                'status' => 1
            ];
            $this->storeOrModifyRecord(credential: $credential, method: 'contactLists', data: $data);
        }
    }

    private function accountContacts(
        array        $integrationPayload,
        array|object $credential,
        string|int   $contactId
    ): void
    {
        if (Arr::has($integrationPayload, 'account') && !empty(Arr::get($integrationPayload, 'account'))) {
            $data['accountContact'] = [
                'account' => Arr::get($integrationPayload, 'account'),
                'contact' => $contactId,
            ];
            if (Arr::has($integrationPayload, 'job_title')) {
                $data['accountContact'] += ['jobTitle' => Arr::get($integrationPayload, 'job_title')];
            }
            $this->storeOrModifyRecord(credential: $credential, method: 'accountContacts', data: $data);
        }
    }

    public function storeOrModifyRecord(
        array|Credential $credential,
        string           $method,
        array            $data)
    {
        $apiConfig = $this->activeCampaignClient->prepareApiRequestWithMethod($credential, $method);
        return http_post($apiConfig['apiEndpoint'], $apiConfig['headers'], $data);
    }

    public function updateRecord(
        string|int   $id,
        array        $data,
        array|object $credential): mixed
    {
        $apiConfig = $this->activeCampaignClient->prepareApiRequestWithMethod($credential, "contacts/{$id}");
        return http_put($apiConfig['apiEndpoint'], $apiConfig['headers'], $data);
    }

}
