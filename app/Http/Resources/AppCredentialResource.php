<?php

namespace App\Http\Resources;

use App\Enums\AuthType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class AppCredentialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'uid' => $this->uid,
            'logo_url' => $this->logo_url,
            'credentials' => $this->formatCredentials($this->credentials),
        ];

    }

    private function formatCredentials($credentials)
    {
        $credentialsGrouped = $credentials->groupBy('source');
        $formattedCredentials = [];

        // list credential as auth group wisen
        foreach ($credentialsGrouped as $authType => $creds) {
            $formattedCredentials[$authType] = $creds->map(function ($credential) use ($authType) {
                // integration count for deleting permissions
                $integration = $credential->actionIntegrations->count();
                //get auth format from config as auth type
                $serviceConfig = config('integration.services.' . $this->pointer . '.meta.' . $authType);
                $metaData = $credential?->secrets ?? null;
                $title = AuthType::getTitle($authType);
                $additionalData = ['uid' => $credential->uid, 'title' => $title, 'integration' => $integration];
                if (!is_null($serviceConfig)) {
                    // Handle the case when the config is not found
                    $metaData = $this->formatMetaData($metaData, $serviceConfig);
                    //throw new \Exception('Service configuration not found.');
                }
                return array_merge($additionalData, $metaData);
            });
        }

        return $formattedCredentials;
    }

    private function formatMetaData(?array $metaData, array $config)
    {
        $formatted = [];
        foreach ($config as $key => $path) {
            $formatted[$key] = data_get($metaData, $path);
        }
        return $formatted;
    }
}
