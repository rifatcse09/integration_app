<?php

namespace App\Services;

use App\Enums\AuthType;
use App\Enums\Status;
use App\Exceptions\CustomException;
use App\Integrations\GoogleSheet\GoogleSheetCredentialService;
use App\Integrations\GoogleSheet\GoogleSheetWebhookProcess;
use App\Jobs\ProcessIntegrationJob;
use App\Models\App;
use App\Models\Credential;
use App\Models\Integration;
use App\Models\WebhookEvent;
use App\Models\WebhookRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use stdClass;

class IntegrationService extends BaseService
{

    public function index($request)
    {

        $search = $request->input('search');
        $actionId = $request->input('action_id');
        $triggerId = $request->input('trigger_id');
        $status = $request->input('status');
        $sortBy = $request->input('sort_by', 'created')  ?? 'created';
        $sortOrder = $request->input('sort_order', 'desc') ?? 'desc';
        $paginate = $request->input('paginate', config('pagination.default'));

        $query = DB::table('integrations')
            ->select(
                'integrations.uid as integration_uid',
                'integrations.name as integration_title',
                DB::raw('COUNT(activity_logs.id) as total_activity'),
                DB::raw('MAX(activity_logs.created_at) as last_run_activity'),
                'integrations.created_at as created',
                'integrations.status',
                'trigger_app.icon as trigger_logo',
                'action_app.icon as action_logo',
            )
            ->leftJoin('apps as trigger_app', 'integrations.trigger_id', '=', 'trigger_app.id')
            ->leftJoin('apps as action_app', 'integrations.action_id', '=', 'action_app.id')
            ->leftJoin('activity_logs', 'activity_logs.integration_id', '=', 'integrations.id')
            ->groupBy(
                'integrations.uid',
                'integrations.name',
                'trigger_app.icon',
                'action_app.icon',
                'integrations.created_at',
                'integrations.status'
            )
            ->where('integrations.shop_id', shop()->id);

        if (isset($status)) {
            $query->where('integrations.status', $status);
        }
        if ($actionId) {
            $query->where('integrations.action_id', $actionId);
        }

        if ($triggerId) {
            $query->where('integrations.trigger_id', $triggerId);
        }
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('integrations.name', 'ILIKE', "%{$search}%");
            });
        }

        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($paginate);

    }

    public function store(array $request)
    {
        $name = Arr::get($request, 'name');
        $shopId = shop()->id;
        $integrationTest = Arr::get($request, 'integration_test');

        $uIds = [
            'trigger_id' => Arr::get($request, 'trigger_uid'),
            'action_id' => Arr::get($request, 'action_uid'),
            'event_id' => Arr::get($request, 'event_uid'),
            'action_credential_id' => Arr::get($request, 'action_credential_uid'),
            'trigger_credential_id' => Arr::get($request, 'trigger_credential_uid')
        ];

        $ids = $this->getIdsByUids($uIds);

        $integration = Integration::create([
            'name' => $name,
            'shop_id' => $shopId,
            'action_id' => $ids['action_id'] ?? null,
            'trigger_id' => $ids['trigger_id'] ?? null,
            'event_id' => $ids['event_id'] ?? null,
            'action_credential_id' => $ids['action_credential_id'] ?? null,
            'trigger_credential_id' => $ids['trigger_credential_id'] ?? null,
            'payload' => Arr::get($request, 'payload'),
            'status' => Status::ACTIVE->value
        ]);

        if ($integration->app) {
            $appPointer = $integration->app->pointer;

            if ($integrationTest) {
                ProcessIntegrationJob::dispatch(integrationId: $integration->id, appPointer: $appPointer, integrationTest: $integrationTest);
            }
        }

        return $integration;
    }

    private function getIdsByUids(array $uids): array
    {
        $ids = [];
        foreach ($uids as $key => $uid) {
            if (!empty($uid)) {
                $model = $this->getModelForKey($key);
                $ids[$key] = getIdByUid($uid, $model);
            }
        }
        return $ids;
    }

    private function getModelForKey(string $key): string
    {
        return match ($key) {
            'trigger_id', 'action_id' => App::class,
            'event_id' => WebhookEvent::class,
            'action_credential_id', 'trigger_credential_id' => Credential::class,
            default => throw new InvalidArgumentException("Invalid key: $key"),
        };
    }

    public function getIntegrationDetails($integrationUid): ?stdClass
    {
        return DB::table('integrations')
            ->select(
                'integrations.id as integration_id',
                'integrations.name as integration_title',
                'integrations.payload as payload',
                'integrations.created_at',
                'integrations.updated_at',
                'credentials.id as credential_id',
                'credentials.name as credential_name',
                'credentials.source as credential_source',
                'credentials.secrets as credential_secrets',
                'webhook_events.id as event_id',
                'webhook_events.name as event_name',
                'webhook_events.topic as event_topic',
                'trigger_app.icon as trigger_logo',
                'action_app.icon as action_logo',
                'action_app.pointer as action_pointer'
            )
            ->leftJoin('credentials', 'integrations.action_credential_id', '=', 'credentials.id')
            ->leftJoin('webhook_events', 'integrations.event_id', '=', 'webhook_events.id')
            ->leftJoin('apps as trigger_app', 'integrations.trigger_id', '=', 'trigger_app.id')
            ->leftJoin('apps as action_app', 'integrations.action_id', '=', 'action_app.id')
            ->where('integrations.uid', $integrationUid)
            ->where('integrations.shop_id', shop()->id)
            ->first();
    }

    public function conditionLogic(array $conditionGroup, array $payload): bool
    {

        $operator = $conditionGroup['operator'] ?? 'AND'; // Default to AND if operator is missing
        $conditions = $conditionGroup['data'] ?? [];

        $results = [];
        foreach ($conditions as $condition) {
            if (isset($condition['field'])) {
                // If the condition has a 'field', it's an individual condition
                $results[] = $this->evaluateCondition($condition['field'], $condition['logic'], $condition['value'], $payload);
            } else {
                // If not, it's a nested condition group, recursively process it
                $results[] = $this->conditionLogic($condition, $payload);
            }
        }

        // Apply the operator logic (AND/OR)
        if ($operator === 'AND') {
            return !in_array(false, $results, true); // If any condition is false, the whole AND group is false
        } elseif ($operator === 'OR') {
            return in_array(true, $results, true); // If any condition is true, the whole OR group is true
        }

        return false;
    }

    protected function evaluateCondition($field, $logic, $value, $payload): bool
    {
        $fieldValue = Arr::get($payload, $field, null);
        return match ($logic) {
            '=' => $fieldValue == $value,
            '>' => $fieldValue > $value,
            '<' => $fieldValue < $value,
            '>=' => $fieldValue >= $value,
            '<=' => $fieldValue <= $value,
            '!=' => $fieldValue != $value,
            'contains' => is_string($fieldValue) && str_contains($fieldValue, (string)$value),
            'not contains' => is_string($fieldValue) && !str_contains($fieldValue, (string)$value),
            'null' => is_null($fieldValue),
            'not null' => !is_null($fieldValue),
            default => false,
        };
    }

    /**
     * Get the webhook payload (test or live).
     *
     * @param Integration $integration The integration instance.
     * @param int|null $webhookRequestId Live webhook request ID (nullable).
     * @param bool $integrationTest Use test payload if true.
     *
     * @return array The webhook payload.
     * @throws CustomException
     */
    public function getWebhookPayload(Integration $integration, ?int $webhookRequestId, bool $integrationTest): array
    {
        if ($integrationTest) {
            return $this->getTestWebhookPayload($integration->event_id);
        }

        return $this->getLiveWebhookPayload($webhookRequestId);
    }

    private function getTestWebhookPayload(int $eventId): array
    {
        $webhookEvent = WebhookEvent::findOrFail($eventId);
        return Arr::get($webhookEvent, 'payload');
    }

    private function getLiveWebhookPayload(?int $webhookRequestId): array
    {
        if (!$webhookRequestId) {
            throw new CustomException('Webhook request not found.');
        }
        $webhookRequest = WebhookRequest::findOrFail($webhookRequestId);
        return $webhookRequest->payload;
    }

    public function getConcatenatedField(array $triggerField, array $webhookPayload): array
    {
        $logMissingStatus = empty($triggerField);
        $values = array_map(function ($item) use ($webhookPayload, &$logMissingStatus) {
            $tag = $item['tag'] ?? null;
            if ($tag) {
                return Arr::get($webhookPayload, $tag);
            } elseif (isset($item['text'])) {
                return $item['text'];
            }
            // If any trigger map field is null, set the status to true
            $logMissingStatus = true;
            return null;
        }, $triggerField);

        return [
            'logStatus' => $logMissingStatus,
            'field' => implode(' ', array_filter($values))
        ];
    }


}
