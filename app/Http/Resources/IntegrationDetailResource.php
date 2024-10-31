<?php

namespace App\Http\Resources;

use App\Enums\AuthType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class IntegrationDetailResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $actionPayload = json_decode($this->payload, true);
        return [
            'title' => $this->integration_title,
            'event_name' => $this->event_name,
            'event_topic' => $this->event_topic,
            'action_event' => str_replace("_", " ", ucfirst(Arr::get($actionPayload, 'module'))),
            'trigger_logo_url' => Storage::disk('public')->url($this->trigger_logo),
            'action_logo_url' => Storage::disk('public')->url($this->action_logo),
            'created' => $this->created_at,
            'updated' => $this->updated_at,
            'action_pointer' => $this->action_pointer,
            'credential' => $this->formatCredentials($this->credential_secrets, $this->action_pointer, $this->credential_source),
        ];
    }

    private function formatCredentials($credentialSecrets, $actionPointer, $authType)
    {
        //get auth format from config as auth type
        $serviceConfig = config('integration.services.' . $actionPointer . '.meta.' . $authType);
        $metaData = json_decode($credentialSecrets, true);
        $title = AuthType::getTitle($authType);
        $additionalData = ['title' => $title];
        $metaData = $this->formatMetaData($metaData, $serviceConfig);
        return array_merge($additionalData, $metaData);

    }

    private function formatMetaData(?array $metaData, ?array $config)
    {
        $formatted = [];
        if ($config) {
        foreach ($config as $key => $path) {
            $formatted[$key] = data_get($metaData, $path);
        }
        }
        return $formatted;
    }
}


