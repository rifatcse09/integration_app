<?php

namespace App\Integrations\ActiveCampaign;

use App\Contacts\CredentialServiceInterface;
use App\Enums\CredentialStatus;
use App\Traits\AuthMethods;
use Illuminate\Support\Arr;

class ActiveCampaignCredentialService implements  CredentialServiceInterface
{

    use AuthMethods;

    public function __construct(protected ActiveCampaignClient $activeCampaignClient)
    {
    }

    public function getSupportedAuthMethods(): array
    {
        return [
            $this->getApiKeyUrlAuth()
        ];
    }

    public function authorize(array $request): array
    {
        $apiKey = Arr::get($request, 'api_key');
        $apiUrl = Arr::get($request, 'api_url');
        $headers = [
            'Api-Token' => $apiKey,
        ];

        $apiEndpoint = $this->activeCampaignClient->apiEndPoint($apiUrl, 'users/me');

        $response = http_get($apiEndpoint, $headers);
        if (!empty($response)) {
           $user =  Arr::get($response,'user');
           $fullName = Arr::get($user,'firstName').' '.Arr::get($user,'lastName');
           $meta = [
               'name' => $fullName,
               'username' =>  Arr::get($user,'username'),
               'email' => Arr::get($user,'email'),
           ];
            return [
                'status' => true,
                'meta' => $meta
            ];
        }

        return [
            'status' => false,  // Indicates failure
        ];


    }

    public function prepareCredentialData(array $request, $shopId, $app): array
    {
        $apiKey = Arr::get($request, 'api_key');
        $apiUrl = Arr::get($request, 'api_url');
        if (!$apiKey && $apiUrl) {
            throw  new \Exception('API key or API url not provided');
        }
        $response = $this->authorize($request);
        if (!$response['status']) {
            throw  new \Exception('Authentication failed');
        }

        return [
            'name' => $request['auth_type'],
            'shop_id' => $shopId,
            'secrets' => [
                'api_key' => $apiKey,
                'api_url' => $apiUrl,
                 'meta' => Arr::get($response, 'meta')
            ],
            'source' => $request['auth_type'],
            'status' => CredentialStatus::ACTIVE->value,
            'app_id' => $app->id,
        ];
    }

}
