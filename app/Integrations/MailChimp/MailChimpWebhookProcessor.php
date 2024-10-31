<?php

namespace App\Integrations\MailChimp;

use App\Enums\LogStatus;
use App\Enums\LogTitle;
use App\Exceptions\ConditionMismatchException;
use App\Exceptions\CustomException;
use App\Models\Integration;
use App\Models\WebhookEvent;
use App\Models\WebhookRequest;
use App\Services\ActivityLogService;
use App\Services\IntegrationService;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class MailChimpWebhookProcessor
{
    private string $accessToken;
    private string $dataCenter;
    private string $listId;
    private string $logTitle;
    private bool $logMissingStatus = false;

    public function __construct(protected MailChimpClient $mailchimpClient, protected IntegrationService $integrationService, protected ActivityLogService $activityLogService)
    {
    }

    /**
     * Process a webhook request for a given integration.
     *
     * @param int|null $webhookRequestId The ID of the webhook request (optional).
     * @param int $integrationId The ID of the integration to process.
     * @param bool $integrationTest Indicates if the integration is in test mode (default: false).
     *
     * @throws CustomException If the integration is not found.
     * @throws ConditionMismatchException If the condition logic fails.
     */
    public function processWebhook(?int $webhookRequestId, int $integrationId, bool $integrationTest = false): void
    {
        $integration = Integration::with('app', 'actionCredential')->find($integrationId);
        if (!$integration) {
            throw new CustomException('Integration not found');
        }

        $integrationPayload = $integration->payload;
        $webhookPayload = $this->integrationService->getWebhookPayload($integration, $webhookRequestId, $integrationTest);

        try {

            if ($integrationPayload['condition_status'] && Arr::has($integrationPayload, 'condition')) {
                $conditionCheckStatus = $this->integrationService->conditionLogic($integrationPayload['condition'], $webhookPayload);
                if (!$conditionCheckStatus) {
                    throw new ConditionMismatchException('Condition mismatch');
                }
            }

            $this->listId = $integrationPayload['audience'];

            $this->initializeMailchimpClient($integration->actionCredential);

            $fieldData = $this->prepareFieldData($integrationPayload, $webhookPayload);

            $apiResponse = $this->processModule($integrationPayload['module'], $fieldData, $integrationPayload);

            $logStatus = $this->logMissingStatus ? LogStatus::MISSING : LogStatus::SUCCESS;
            $logTitle = $this->logTitle;

            $this->activityLogService->logActivity($integration, $webhookPayload, $apiResponse, $logTitle, $logStatus);


        } catch (\Throwable $e) {
            $this->activityLogService->logError($integration, $webhookPayload, $e);
        }
    }

    /**
     * Initializes the Mailchimp client with the provided action credentials.
     *
     * @param mixed $actionCredential The action credential object used to retrieve the access token and data center.
     *
     * @return void This method does not return a value.
     */
    private function initializeMailchimpClient(mixed $actionCredential): void
    {
        $this->accessToken = $this->mailchimpClient->getAccessToken($actionCredential);
        $this->dataCenter = $this->mailchimpClient->getDataCenter($actionCredential, $this->accessToken);
    }

    private function prepareFieldData(array $integrationPayload, array $webhookPayload): array
    {
        $fieldData = [];
        $mergeFields = [];

        foreach ($integrationPayload['map'] as $index => $map) {
            $concatenatedData = $this->integrationService->getConcatenatedField($map['value'], $webhookPayload);
            $concatenatedString = $concatenatedData['field'];
            // If any iteration sets logStatus to true, the final logMissingStatus will be true
            $this->logMissingStatus = $this->logMissingStatus || $concatenatedData['logStatus'];
            $actionField = $map['name'];
            if ($actionField === 'email_address') {
                if (!$concatenatedString) {
                    throw new CustomException('Mapping Email not found');
                }
                $fieldData['email_address'] = $concatenatedString;
            } elseif ($actionField === 'BIRTHDAY') {
                if (!$concatenatedString) {
                    throw new CustomException('Birthday not found');
                }
                if (isValidDateString($concatenatedString)) {
                    throw new CustomException('The Birthday is not a valid date.');
                }
                $mergeFields[$actionField] = date('m/d', strtotime($concatenatedString));
            } else {
                $mergeFields[$actionField] = $concatenatedString;
            }
        }
        if (!empty($mergeFields)) {
            $fieldData['merge_fields'] = $mergeFields;
        }

        $fieldData['tags'] = $integrationPayload['tags'] ?? [];
        $fieldData['status'] = $integrationPayload['double_opt_in'] ? 'pending' : 'subscribed';
        $fieldData['double_optin'] = $integrationPayload['double_opt_in'];

        if ($integrationPayload['address']) {
            // all required address field not find
            $this->logMissingStatus = $this->processAddress($integrationPayload, $webhookPayload, $fieldData);
        }

        return $fieldData;
    }

    private function getConcatenatedField(array $triggerField, array $webhookPayload): string
    {
        $values = array_map(function ($item) use ($webhookPayload) {
            $tag = $item['tag'] ?? null;
            if ($tag) {
                return Arr::get($webhookPayload, $tag);
            } elseif (isset($item['text'])) {
                return $item['text'];
            }
            // if any trigger map field null
            $this->logMissingStatus = true;
            return null;
        }, $triggerField);

        return implode(' ', array_filter($values));
    }

    private function processAddress(array $integrationPayload, array $webhookPayload, array &$fieldData): bool
    {
        $addressFields = $integrationPayload['address_field'];

        // Map the address fields
        $addressValues = array_filter(array_map(function ($item) use ($webhookPayload) {
            $value = Arr::get($webhookPayload, $item['value'] ?? null);
            return [
                $item['name'] => $value
            ];
        }, $addressFields));

        // Combine address data
        $addressValues = array_merge(...$addressValues);

        // Remove keys with empty values
        $addressValues = array_filter($addressValues, function ($value) {
            return !empty($value);
        });

        // Required fields
        $requiredFields = ['addr1', 'city', 'state', 'zip'];

        // Check if required fields are present and not empty
        $addressComplete = !array_diff($requiredFields, array_keys($addressValues)) &&
            array_reduce($requiredFields, function ($carry, $field) use ($addressValues) {
                return $carry && !empty($addressValues[$field]);
            }, true);


        // If all required fields are present and non-empty, set the address in merge fields
        if ($addressComplete) {
            $fieldData['merge_fields']['ADDRESS'] = $addressValues;
            return true;
        }
        return false;
    }

    private function processModule(string $module, array $fieldData, array $integrationPayload): array
    {
        if ($module === 'add_a_member_to_an_audience') {
            if ($integrationPayload['update']) {
                $contactEmail = $fieldData['email_address'];
                $foundContact = $this->existContact($contactEmail);
                if (count($foundContact)) {
                    $contactId = $foundContact[0]->id;
                    $apiResponse = $this->updateRecord($contactId, $fieldData);
                    $this->logTitle = LogTitle::MEMBER_UPDATED->value;
                } else {
                    throw new CustomException('Existing member data not found', ['email' => $contactEmail]);
                }
            } else {
                $data['field_data'] = $fieldData;
                $apiResponse = $this->addListMember($data);
                $this->logTitle = LogTitle::MEMBER_ADDED->value;
            }
        } elseif (in_array($module, ['add_tag_to_a_member', 'remove_tag_from_a_member'])) {
            if (empty($fieldData['tags'])) {
                throw new CustomException('Tags are required');
            }
            $apiResponse = $this->addRemoveTag($module, $fieldData['tags'], $fieldData['email_address']);
        }

        return $apiResponse ?? [];
    }

    public function existContact(string $queryParam): array
    {
        $query = "{$queryParam}";
        $listId = $this->listId;
        $existSearch = $this->mailchimpClient->apiEndPoint($this->accessToken, $this->dataCenter)->searchMembers->search($query);
        return array_filter($existSearch->exact_matches->members, function ($member) use ($listId) {
            return $member->list_id === $listId;
        });
    }

    public function addListMember(array $request): array
    {
        $fieldData = $request['field_data'];
        return parseResponse($this->mailchimpClient->apiEndPoint($this->accessToken, $this->dataCenter)->lists->addListMember($this->listId, $fieldData));
    }

    public function addRemoveTag(string $module, array $inputTags, string $email): ?array
    {
        $tags = [];
        $isActive = $module == 'add_tag_to_a_member';
        $subscriber_hash = md5(strtolower(trim($email)));
        foreach ($inputTags as $value) {
            $tags['tags'][] = ['name' => $value, 'status' => $isActive ? 'active' : 'inactive'];
        }
        $this->logTitle = $module == 'add_tag_to_a_member' ? LogTitle::TAG_ADDED->value : LogTitle::TAG_REMOVED->value;
        $endpoint = $this->mailchimpClient->apiEndPoint($this->accessToken, $this->dataCenter);
        return $endpoint->lists->updateListMemberTags($this->listId, $subscriber_hash, $tags) ?? $tags;

    }

    public function updateRecord(string $contactId, array $fieldData): array
    {
        return parseResponse($this->mailchimpClient->apiEndPoint($this->accessToken, $this->dataCenter)->lists->setListMember($this->listId, $contactId, $fieldData));
    }

}
