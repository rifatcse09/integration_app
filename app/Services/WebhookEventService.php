<?php

namespace App\Services;

use App\Models\CustomWebhook;
use App\Models\WebhookEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class WebhookEventService extends BaseService
{
    /**
     * Create a new class instance.
     */
    public function getEventsByAppId($appId)
    {
        return WebhookEvent::where('app_id', $appId)->get();
    }

    public function getEventsByUid($uid): ?Model
    {
        return WebhookEvent::with(['app' => function ($query) {
            return $query->select('id','pointer');
        }])->where('uid', $uid)->select('app_id')->first();
    }

    public function getPayloadByEventId($eventId = null, string $triggerPointer): array
    {
        $fields = [];
        if (!$eventId) {
            return $fields;
        }
        $eventData = WebhookEvent::where('uid', $eventId)->first();

        if (Arr::has($eventData, 'payload')) {

            $payload = Arr::get($eventData, 'payload');
            $webhookTopicsData = config('integration.services.'.$triggerPointer.'.topics_data', []);
            $requiredFields = [];

            // Get the required fields for filtering webhook attributes
            if (isset($webhookTopicsData[$eventData->topic])) {
                $requiredFields = config('integration.services.' . $webhookTopicsData[$eventData->topic]['selected_fields']);
            }

            // Extract webhook attributes for required fields from  webhook payload
            if (!empty($requiredFields)) {
                $payload = $this->extractFields($payload, $requiredFields);
            }

            return $this->processNestedArray($payload);

        }

        return $fields;
    }

    private function processNestedArray(array $array, string $prefix = ''): array
    {
        $fields = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : "$prefix.$key";

            if (is_array($value)) {
                // Recursive call to handle deeper levels of the array
                $fields = array_merge($fields, $this->processNestedArray($value, $newKey));
            } else {
                // Add the final field to the result array
                $fields[] = [
                    'value' => $newKey,
                    'label' => snakeToTitleCase($newKey),
                ];
            }
        }

        return $fields;
    }

    private function extractFields(array $sourceArray, array $requiredFields)
    {
        $result = [];
        foreach ($requiredFields as $field) {
            $keys = explode('.', $field);
            $temp = &$result;

            foreach ($keys as $key) {
                if (!isset($temp[$key])) {
                    $temp[$key] = [];
                }
                $temp = &$temp[$key];
            }

            $temp = $this->getNestedValue($sourceArray, $field);
        }
        return $result;
    }

    private function getNestedValue(array $array, string $path)
    {
        $keys = explode('.', $path);
        $value = $array;
        foreach ($keys as $key) {
            if (isset($value[$key])) {
                $value = $value[$key];
            } else {
                return null;
            }
        }
        return $value;
    }

    public function getEventsByUniqueId($appId, $uniqueId)
    {
        $customWebhook = CustomWebhook::where('unique_code', $uniqueId)->where('shop_id', shop()->id)->first();

        if (!$customWebhook) {
           throw  new \Exception('Webhook not found');
        }

      return WebhookEvent::where('custom_webhook_id', $customWebhook->id)->where('app_id', $appId)->first();

    }


}
