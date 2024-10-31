<?php

namespace App\Integrations\GoogleSheet;

use App\Enums\LogStatus;
use App\Enums\LogTitle;
use App\Exceptions\ConditionMismatchException;
use App\Exceptions\CustomException;
use App\Models\Integration;
use App\Services\ActivityLogService;
use App\Services\IntegrationService;
use Illuminate\Support\Arr;

class GoogleSheetWebhookProcess
{

    private bool $logMissingStatus = false;
    private string $logTitle;

    public function __construct(protected IntegrationService $integrationService, protected GoogleSheetCredentialService $googleSheetCrdSvc, protected ActivityLogService $activityLogService)
    {
    }

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

            $credentialUid = $integration->actionCredential->uid;

            $workSheetHeaders = $this->fetchLatestWorksheetHeaders($integrationPayload, $credentialUid);

            $fieldData = $this->prepareFieldData($integrationPayload, $webhookPayload, $workSheetHeaders);

            $apiResponse = $this->processModule($fieldData, $integrationPayload, $credentialUid);

            $logStatus = $this->logMissingStatus ? LogStatus::MISSING : LogStatus::SUCCESS;
            $logTitle = $this->logTitle;

            $this->activityLogService->logActivity($integration, $webhookPayload, $apiResponse, $logTitle, $logStatus);


        } catch (\Throwable $e) {
            $this->activityLogService->logError($integration, $webhookPayload, $e);
        }


    }

    private function prepareFieldData(array $integrationPayload, array $webhookPayload, array $workSheetHeaders): array
    {
        $fieldData = [];

        foreach ($integrationPayload['map'] as $index => $map) {
            $concatenatedData = $this->integrationService->getConcatenatedField($map['value'], $webhookPayload);
            $concatenatedString = $concatenatedData['field'];
            // If any iteration sets logStatus to true, the final logMissingStatus will be true
            $this->logMissingStatus = $this->logMissingStatus || $concatenatedData['logStatus'];

            $actionField = $map['name'];
            $fieldData[$actionField] = $concatenatedString;

        }

        $values = [];

        foreach ($workSheetHeaders as $googleSheetHeader) {

            $header = $googleSheetHeader['value'];
            if (!empty($fieldData[$header])) {
                $values[] = $fieldData[$header];
            } else {
                $values[] = '';
            }
        }

        $data = [];
        $worksheetName = Arr::get($integrationPayload, 'worksheet_name');
        $headerRow = Arr::get($integrationPayload, 'header_row');
        $header = Arr::has($integrationPayload, 'header') ? Arr::get($integrationPayload, 'header') : 'ROWS';
        $data['range'] = "{$worksheetName}!{$headerRow}";
        $data['majorDimension'] = "{$header}";
        $data['values'][] = $values;
        return $data ?? [];
    }

    private function fetchLatestWorksheetHeaders(array $integrationPayload, string $credentialUid)
    {
        unset($integrationPayload['map'], $integrationPayload['condition']);
        if (!Arr::has($integrationPayload, 'header')) {
            Arr::set($integrationPayload, 'header', 'ROWS');
        }
        Arr::set($integrationPayload, 'credential_uid', $credentialUid);
        $googleSheetIntegration = app(GoogleSheetIntegration::class);
        return $googleSheetIntegration->getWorkSheetHeaders($integrationPayload);
    }

    private function processModule(array $fieldData, array $integrationPayload, string $credentialUid): array
    {

        $spreadsheetsId =  Arr::get($integrationPayload, 'spreadsheet_id');
        $worksheetName = Arr::get($integrationPayload, 'worksheet_name');
        $headerRow = Arr::get($integrationPayload, 'header_row');

        $googleSheetIntegration = app(GoogleSheetIntegration::class);
        $accessToken = $googleSheetIntegration->getAccessToken($credentialUid);
        $authorizationHeader['Authorization'] = "Bearer {$accessToken}";
        $insertRecordEndpoint = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetsId}/values/{$worksheetName}!{$headerRow}:append?valueInputOption=USER_ENTERED";
        $apiResponse = http_post($insertRecordEndpoint, $authorizationHeader, $fieldData);
        $this->logTitle = LogTitle::MEMBER_ADDED->value;
        return $apiResponse ?? [];
    }
}
