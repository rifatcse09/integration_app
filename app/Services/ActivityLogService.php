<?php

namespace App\Services;

use App\Enums\LogStatus;
use App\Exceptions\ConditionMismatchException;
use App\Exceptions\CustomException;
use App\Models\ActivityLog;
use App\Models\Integration;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivityLogService extends BaseService
{
    public function index($request): LengthAwarePaginator
    {

        $search = $request->input('search');
        $status = $request->input('status');
        $sortBy = $request->input('sort_by', 'created')  ?? 'created';
        $sortOrder = $request->input('sort_order', 'desc') ?? 'desc';
        $actionId = $request->input('action_id');
        $triggerId = $request->input('trigger_id');
        $paginate = $request->input('paginate', config('pagination.default'));

        $query = DB::table('activity_logs')
            ->select(
                'activity_logs.uid as log_uid',
                'activity_logs.integration_id',
                'activity_logs.title as activity_title',
                'activity_logs.log_payload',
                'activity_logs.created_at as created',
                'integrations.name as integration_title',
                'trigger_app.icon as trigger_logo',
                'action_app.icon as action_logo',
                'integrations.status',
            )
            ->leftJoin('integrations', 'activity_logs.integration_id', '=', 'integrations.id')
            ->leftJoin('apps as trigger_app', 'integrations.trigger_id', '=', 'trigger_app.id')
            ->leftJoin('apps as action_app', 'integrations.action_id', '=', 'action_app.id')
            ->where('activity_logs.shop_id', shop()->id);

        if ($status) {
            $query->where('integrations.status', $status);
        }
        if ($actionId) {
            $query->where('integrations.action_id', $actionId);
        }
        if ($triggerId) {
            $query->where('integrations.trigger_id', $triggerId);
        }
        if ($search) {
            $query->where('activity_logs.title', 'ILIKE', "%{$search}%")
                ->orWhere('integrations.name', 'ILIKE', "%{$search}%");
        }

        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($paginate);

    }

    public function activitiesByIntegrationUid($request, $integrationUid)
    {
        $paginate = $request->input('paginate', config('pagination.default'));
        return ActivityLog::join('integrations', 'activity_logs.integration_id', '=', 'integrations.id')
            ->where('integrations.uid', $integrationUid)
            ->orderBy('activity_logs.created_at', 'desc')
            ->select('activity_logs.*')
            ->paginate($paginate);

    }

    public function logActivity(Integration $integration, array $webhookPayload, array $apiResponse, ?string $logTitle = null, LogStatus $logStatus = LogStatus::SUCCESS): ActivityLog
    {
        return ActivityLog::create([
            'shop_id' => $integration->shop_id,
            'integration_id' => $integration->id,
            'title' => $logTitle,
            'status' => $logStatus->value,
            'log_payload' => $apiResponse,
            'trigger_payload' => $webhookPayload,
        ]);
    }

    public function logError(Integration $integration, array $webhookPayload, \Throwable $e): ActivityLog
    {
        $logPayload = $this->generateLogPayload($e);
        $title = $logPayload['title'] ?? $e->getMessage();

        Log::error('Error in integration processing', [
            'error' => $e,
            'integration_id' => $integration->id,
            'shop_id' => $integration->shop_id
        ]);

        return ActivityLog::create([
            'shop_id' => $integration->shop_id,
            'integration_id' => $integration->id,
            'title' => $title,
            'log_payload' => $logPayload,
            'trigger_payload' => $webhookPayload,
            'status' => LogStatus::FAILED->value,
        ]);
    }

    private function generateLogPayload(\Throwable $e): array
    {
        return match (true) {
            $e instanceof ClientException => $this->handleClientException($e),
            $e instanceof CustomException => $e->getErrorPayload(),
            $e instanceof ConditionMismatchException => $e->getErrorPayload(),
            default => [
                "code" => "NOT_SUPPORTED_FEATURE",
                "details" => [],
                "message" => "Your edition doesn't support this feature",
                "status" => "error"
            ],
        };
    }

    protected function handleClientException(ClientException $e): array
    {
        $responseBody = $e->getResponse()->getBody()->getContents();
        return parseResponse(json_decode($responseBody));
    }
}
