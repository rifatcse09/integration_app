<?php
declare(strict_types=1);

namespace App\Integrations\MailChimp;

use App\Enums\WebhookType;
use App\Models\WebhookEvent;
use App\Services\AppService;
use App\Services\CredentialService;
use App\Services\WebhookEventService;
use App\Traits\AuthMethods;
use Illuminate\Support\Arr;


class MailChimpIntegration
{
    protected ?string $accessToken = null;

    public function __construct(
        protected MailChimpClient           $mailchimpClient,
        protected CredentialService         $credentialService,
        protected AppService                $appService,
        protected MailChimpWebhookProcessor $webhookProcessor,
        protected WebhookEventService       $webhookEventService)
    {

    }

    public function processWebhook(
        ?int $webhookRequestId,
        int  $integrationId,
        bool $integrationTest = true
    ): void
    {
        $this->webhookProcessor->processWebhook($webhookRequestId, $integrationId, $integrationTest);
    }

    /**
     * Retrieve a list of available modules.
     *
     * @return array Returns an array of modules with their names and labels.
     */
    public function getModules(): array
    {
        return [
            'modules' => [

                [
                    'name' => 'add_a_member_to_an_audience',
                    'label' => 'Add a member to an audience',
                ],
                [
                    'name' => 'add_tag_to_a_member',
                    'label' => 'Add tag to a member',
                ],
                [
                    'name' => 'remove_tag_from_a_member',
                    'label' => 'Remove tag from a member',
                ],

            ]
        ];
    }

    /**
     * Retrieve audience lists from Mailchimp.
     *
     * @param array $request The request array containing 'credential_uid'.
     * @return array Returns an array of audience lists with 'listId' and 'listName'.
     */
    public function getAudiences(array $request): array
    {
        $credentialUid = Arr::get($request, 'credential_uid');
        $credential = $this->credentialService->getCredentialByUidShopId($credentialUid, shop()->id);

        $accessToken = $this->mailchimpClient->getAccessToken($credential);
        $dataCenter = $this->mailchimpClient->getDataCenter($credential, $accessToken);

        $response = $this->mailchimpClient->apiEndPoint($accessToken, $dataCenter)->lists->getAllLists();

        $audienceLists = $response->lists;
        $allList = [];

        foreach ($audienceLists as $audienceList) {

            $allList[] = [
                'listId' => $audienceList->id,
                'listName' => $audienceList->name,
            ];
        }

        return $allList;
    }

    /**
     * Retrieve audience fields based on the request data.
     *
     * @param array $request The request array with 'credential_uid', 'audience_id', and 'module'.
     * @return array Returns audience fields or an empty array if data is missing.
     * @throws \Exception If the credential, access token, or data center is not found.
     */
    public function getAudienceFields(array $request): array
    {
        $credentialUid = Arr::get($request, 'credential_uid');
        $audienceId = Arr::get($request, 'audience_id');
        $module = Arr::get($request, 'module');

        if (!$credentialUid || !$audienceId || !$module) {
            return ['audienceField' => []];
        }

        if ($this->isTagModule($module)) {
            return $this->getTagModuleFields();
        }

        $credential = $this->credentialService->getCredentialByUidShopId($credentialUid, shop()->id);

        if (!$credential) {
            throw new \Exception('Credential not found');
        }

        $accessToken = $this->mailchimpClient->getAccessToken($credential);
        $dataCenter = $this->mailchimpClient->getDataCenter($credential, $accessToken);

        if (empty($accessToken) || empty($dataCenter)) {
            throw new \Exception('Access token or data center not found');
        }
        $mergeFieldResponse = $this->mailchimpClient->apiEndPoint($accessToken, $dataCenter)->lists->getListMergeFields($audienceId);

        return $this->formatResponse($mergeFieldResponse);
    }

    /**
     * Retrieve audience tags from Mailchimp based on the provided request data.
     *
     * This method fetches the list of tags (segments) for a specific audience using the provided
     * credential and audience ID. If credentials or audience data is missing or invalid, an empty
     * array is returned. The tags are retrieved through the Mailchimp API.
     *
     * @param array $request The request array containing 'credential_uid' and 'audience_id'.
     *
     * @return array Returns an array with the audience tags. If no tags are found or an exception occurs,
     *               it returns an empty 'audienceTags' array.
     */
    public function getTags(array $request): array
    {
        $allList = [];
        $credentialUid = Arr::get($request, 'credential_uid');
        $audienceId = Arr::get($request, 'audience_id');

        if (!$credentialUid || !$audienceId) {
            return ['audienceTags' => $allList];
        }

        try {
            $credential = $this->credentialService->getCredentialByUidShopId($credentialUid, shop()->id);
            if (!$credential) {
                return ['audienceTags' => $allList];
            }

            $accessToken = $this->mailchimpClient->getAccessToken($credential);
            $dataCenter = $this->mailchimpClient->getDataCenter($credential, $accessToken);

            $tagsList = $this->mailchimpClient->apiEndPoint($accessToken, $dataCenter)->lists->listSegments($audienceId);

            if (empty($tagsList->segments)) {
                return ['audienceTags' => $allList];
            }

            foreach ($tagsList->segments as $tag) {
                $allList[] = [
                    'tagId' => $tag->id,
                    'tagName' => $tag->name,
                ];
            }
        } catch (\Exception $e) {

            return ['audienceTags' => $allList];
        }

        return ['audienceTags' => $allList];
    }

    /**
     * Check if the given module is a tag module.
     *
     * @param string $module The module name to check.
     * @return bool Returns true if the module is a tag module, otherwise false.
     */
    private function isTagModule(string $module): bool
    {
        return in_array($module, ['add_tag_to_a_member', 'remove_tag_from_a_member']);
    }

    /**
     * Retrieve the default tag module fields.
     *
     * This method returns a predefined array structure for tag module fields, which includes
     * an audience field with email address details. Each field includes a value, label,
     * and required status, along with options.
     *
     * @return array Returns an array containing audience field information, including
     *               value, label, required status, and options for each field.
     */
    private function getTagModuleFields(): array
    {
        return [
            'audienceField' => [
                [
                    'value' => 'email_address',
                    'label' => 'Email',
                    'required' => true,
                    "options" => [
                        'value' => 'email_address',
                        'label' => 'Email',
                        'required' => true
                    ]
                ]
            ]
        ];
    }

    /**
     * Format the response by processing merge fields and generating a list of fields.
     *
     * @param object $mergeFieldResponse An object containing the merge fields from an API response.
     *
     * @return array Returns an array containing the formatted audience fields.
     */
    private function formatResponse(object $mergeFieldResponse): array
    {
        $fields = [];
        if ($mergeFieldResponse && isset($mergeFieldResponse->merge_fields)) {
            $fields [] = [
                'value' => 'email_address',
                'label' => 'Email',
                'required' => true
            ];
            foreach ($mergeFieldResponse->merge_fields as $field) {
                if ($field->name === 'Address') {
                    continue;
                }
                $fields[] = [
                    'value' => $field->tag,
                    'label' => $field->name,
                    'required' => $field->required ?? false
                ];
            }
        }
        $finalFields = [];
        foreach ($fields as $field) {
            // Clone all fields and remove the 'required' field from options
            $options = array_map(function ($f) {
                $fWithoutRequired = $f;
                unset($fWithoutRequired['required']);
                return $fWithoutRequired;
            }, $fields);

            // Add options to the field
            $field['options'] = $options;

            // Push to final fields array
            $finalFields[] = $field;
        }

        return ['audienceField' => $finalFields];
    }

    /**
     * Retrieve initial data based on the decrypted state, including service information,
     * modules, audiences, events, and payload.
     *
     * @param array $decryptedState The decrypted state array containing keys like 'credential_uid',
     *                              'trigger_uid', and 'event_uid'.
     *
     * @return array Returns an array with service information, modules, audiences, events,
     *               payload data, and logo URLs for the trigger and action.
     */
    public function getInitialData(array $decryptedState): array
    {
        $credentialUid = Arr::get($decryptedState, 'credential_uid');
        $triggerUid = Arr::get($decryptedState, 'trigger_uid');
        $eventUid = Arr::get($decryptedState, 'event_uid');

        $app = $this->appService->getAppByUid($triggerUid);
        $modules = $this->getModules();

        $audiences = $this->getAudiences(['credential_uid' => $credentialUid]);
        $webhookEvent = $app->type == WebhookType::SHOPIFY->value ? WebhookEvent::where('app_id', $app->id)->select('uid', 'name', 'topic')->get() : [];

        $credential = $this->credentialService->getCredentialByUidShopId($credentialUid, shop()->id);
        $actionUid = $credential->app->uid;
        $actionInfo = $this->appService->getAppByUid($actionUid);
        $service = $credential->app->pointer;

        $payload =  $this->webhookEventService->getPayloadByEventId($eventUid, $app->pointer);

        return [
            'trigger_name' => $app->pointer,
            'service' => $service,
            'modules' => $modules['modules'],
            'audience_list' => $audiences,
            'event_list' => $webhookEvent,
            'action_uid' => $actionUid,
            'credential_uid' => $credentialUid,
            'trigger_uid' => $triggerUid,
            'event_uid' => $eventUid,
            'event_payload' => $payload,
            'trigger_logo_url' => $app->logo_url,
            'action_logo_url' => $actionInfo->logo_url,
        ];
    }

}
