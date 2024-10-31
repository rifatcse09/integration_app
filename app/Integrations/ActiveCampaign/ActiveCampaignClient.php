<?php

namespace App\Integrations\ActiveCampaign;

use App\Models\Credential;
use App\Services\CredentialService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ActiveCampaignClient
{

    public function apiEndPoint(string $apiUrl, string $method)
    {
        return "{$apiUrl}/api/3/{$method}";
    }

    /**
     * @param Credential $credential The Credential object containing API credentials.
     *
     * @return array An associative array with two keys:
     *               - 'apiKey': The API key extracted from the credentials.
     *               - 'apiUrl': The API URL extracted from the credentials.
     *
     * @throws \Exception If the API key or URL is not found in the provided credentials.
     */
    public function extractApiCredentials(Credential $credential): array
    {
        $secrets = Arr::get($credential, 'secrets');
        $apiUrl = Arr::get($secrets, 'api_url');
        $apiKey = Arr::get($secrets, 'api_key');

        if (empty($apiKey) || empty($apiUrl)) {
            throw new \Exception('API key and URL not found');
        }
        return ['apiKey' => $apiKey, 'apiUrl' => $apiUrl];
    }

    public function prepareApiRequestWithMethod(array|Credential $request, string $method): array
    {
        $credentialUid = Arr::has($request, 'credential_uid') ? Arr::get($request, 'credential_uid') : Arr::get($request, 'uid');
        // Fetch the credential based on UID and shop ID
        $credentialService = app(CredentialService::class);
        $credential = $credentialService->getCredentialByUid($credentialUid);
        if ($credential === null) {
            throw new \Exception('Credential not found for the provided UID and shop ID.');
        }

        // Extract API credentials from the credential data
        $extractCredentials = $this->extractApiCredentials($credential);
        $apiKey = $extractCredentials['apiKey'];
        $headers = ['Api-Token' => $apiKey];

        $apiEndpoint = $this->apiEndPoint($extractCredentials['apiUrl'], $method);

        return [
            'apiEndpoint' => $apiEndpoint,
            'headers' => $headers
        ];
    }
}
