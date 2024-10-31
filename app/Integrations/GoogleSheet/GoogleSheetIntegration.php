<?php

namespace App\Integrations\GoogleSheet;

use App\Contacts\IntegrationContractService;
use App\Enums\WebhookType;
use App\Http\Resources\WebhookEventResource;
use App\Models\App;
use App\Services\AppService;
use App\Services\CredentialService;
use App\Services\WebhookEventService;
use App\Traits\AuthMethods;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class GoogleSheetIntegration
{

    public function __construct(
        protected GoogleSheetCredentialService $googleSheetCrdSvc,
        protected CredentialService            $credentialService,
        protected GoogleSheetWebhookProcess    $googleSheetWebhookProcess,
        protected AppService                   $appService,
        protected WebhookEventService          $webhookEventService
    )
    {

    }

    public function getSpreadSheets(array $request): array
    {
        $credentialUid = Arr::get($request, 'credential_uid');
        $accessToken = $this->getAccessToken($credentialUid);
        $workSheets = config('integration.services.google_sheet.files');
        $authorizationHeader['Authorization'] = "Bearer {$accessToken}";
        $params = ['q' => "mimeType='application/vnd.google-apps.spreadsheet'"];
        $response = http_get($workSheets, $authorizationHeader, $params);

        $spreadsheets = $response['files'];
        $files = [];
        foreach ($spreadsheets as $spreadsheet) {
            $files[] = [
                'name' => $spreadsheet['id'],
                'label' => $spreadsheet['name'],
            ];
        }
        uksort($files, 'strnatcasecmp');
        return ['spreadSheets' => $files];
    }

    public function getWorksheets(array $request): array
    {
        $credentialUid = Arr::get($request, 'credential_uid');
        $accessToken = $this->getAccessToken($credentialUid);
        $spreadsheetId = Arr::get($request, 'spreadsheet_id');

        $worksheetsMetaApiEndpoint = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}?&fields=sheets.properties";

        $authorizationHeader['Authorization'] = "Bearer {$accessToken}";
        $response = http_get($worksheetsMetaApiEndpoint, $authorizationHeader);
        $sheets = $response['sheets'];
        $workSheets = [];

        foreach ($sheets as $sheet) {
            $properties = $sheet['properties'];

            $workSheets[] = [
                'name' => $properties['title'],
                'label' => $properties['title'],
            ];
        }
        return ['workSheets' => $workSheets];
    }

    public function getWorkSheetHeaders(array $request): array
    {
        $credentialUid = Arr::get($request, 'credential_uid');
        $accessToken = $this->getAccessToken($credentialUid);
        $spreadSheetId = Arr::get($request, 'spreadsheet_id');
        $workSheetName = Arr::get($request, 'worksheet_name');

        $headerRow = Arr::get($request, 'header_row');
        $header = Arr::has($request, 'header') ? Arr::get($request, 'header') : 'ROWS';

        if ($header === 'ROWS') {
            $rangeNumber = preg_replace('/[^0-9]/', '', $headerRow);
            $range = "{$headerRow}:ZZ{$rangeNumber}";
        } else {
            $columnLetter = preg_replace('/\d/', '', $headerRow);
            $range = "{$headerRow}:{$columnLetter}1005";
        }

        $worksheetHeadersMetaApiEndpoint = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadSheetId}/values/{$workSheetName}!{$range}?majorDimension={$header}";
        $authorizationHeader['Authorization'] = "Bearer {$accessToken}";
        $response = http_get($worksheetHeadersMetaApiEndpoint, $authorizationHeader);
        return $this->formatResponse($response);
    }

    private function formatResponse(array $response): array
    {
        $fields = [];
        if ($response) {
            foreach ($response['values'] as $key => $field) {
                foreach ($field as $value) {
                    $fields[] = [
                        'value' => $value,
                        'label' => $value,
                        'required' => false
                    ];
                }
            }
        }

        $finalFields = [];
        foreach ($fields as $field) {
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
        return $finalFields;
    }

    public function getAccessToken(string $credentialUid): string
    {
        $credential = $this->credentialService->getCredentialByUid($credentialUid);
        return $this->googleSheetCrdSvc->getAccessToken($credential->id);
    }

    public function getInitialData(array $decryptedState): array
    {
        $credentialUid = Arr::get($decryptedState, 'credential_uid');
        $triggerUid = Arr::get($decryptedState, 'trigger_uid');
        $eventUid = Arr::get($decryptedState, 'event_uid');

        $app = $this->appService->getAppByUid($triggerUid);
        $spreadSheets = $this->getSpreadSheets($decryptedState);

        $webhookEvents = $app->type == WebhookType::SHOPIFY->value ? WebhookEventResource::collection($this->webhookEventService->getEventsByAppId($app->id)): [];

        $credential = $this->credentialService->getCredentialByUidShopId($credentialUid, shop()->id);
        $actionUid = $credential->app->uid;
        $actionInfo = $this->appService->getAppByUid($actionUid);

        $payload = $this->webhookEventService->getPayloadByEventId($eventUid, $app->pointer);

        $service = $credential->app->pointer;

        return [
            'trigger_name' => $app->pointer,
            'service' => $service,
            'spread_sheets' => $spreadSheets['spreadSheets'],
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
        $this->googleSheetWebhookProcess->processWebhook($webhookRequestId, $integrationId, $integrationTest);
    }
}
