<?php

namespace App\Services;

use App\DTOs\CredentialDTO;
use App\Enums\AuthType;
use App\Enums\CredentialStatus;
use App\Models\Credential;
use App\Models\Shop;
use App\Services\Factory\CredentialFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Mockery\Exception;

class CredentialService extends BaseService
{
    public function __construct(protected AppService $appService)
    {

    }

    public function storeCredentials(CredentialDTO $credentialDTO): Credential
    {
        return Credential::create($credentialDTO->toArray());

    }

    public function handleAuthSelection(array $request): array
    {
        if (!$request['auth_type']) {
            throw new \Exception(' Select auth type');
        }
        return match ($request['auth_type']) {
            AuthType::OAUTH2->value, AuthType::CLIENT_SECRET->value => $this->oAuth($request),
            AuthType::API_KEY->value => $this->credentials($request),
            default => [],
        };
    }

    public function oAuth(array $request = []): array
    {
        $authType = Arr::get($request, 'auth_type');
        $eventUid = Arr::get($request, 'event_uid');
        $triggerUid = Arr::get($request, 'trigger_uid');
        $actionUid = Arr::get($request, 'action_uid');
        $app = $this->appService->getAppByUid($actionUid);

        $service = CredentialFactory::make($app->pointer);

        // Get credentials form env or user
        $credential = $service->retrieveOauthCredentials($request);
        $clientId = Arr::get($credential, 'clientId');
        $clientSecret = Arr::get($credential, 'clientSecret');
        $oauthCallback = Arr::get($credential, 'oauthCallback');

        $shopId = shop()->id;
        $secrets = [];
        if ($authType == AuthType::CLIENT_SECRET->value) {
            // when client input credentials storing for edit option
            $secrets = [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $oauthCallback,
            ];
        }

        // Prepare the credential data to be stored.
        $credentialData = [
            'name' => $authType,
            'shop_id' => $shopId,
            'secrets' => $secrets,
            'source' => $authType,
            'status' => CredentialStatus::INACTIVE->value,
            'app_id' => $app->id,
        ];

        // Create a Credential Data Transfer Object (DTO) and store the credentials.
        $credentialData = new CredentialDTO($credentialData);
        $credential = $this->storeCredentials($credentialData);

        if (!$credential->id) {
            throw new Exception('Credential not found');
        }

        // Encrypt and encode the necessary data for the OAuth state parameter.
        $base64EncodedData = encryptAndEncode([
            'credential_uid' => $credential->uid,
            'shop_id' => $shopId,
            'event_uid' => $eventUid,
            'trigger_uid' => $triggerUid,
        ]);

        // Build the authorization URL to redirect the user for OAuth authorization.
        $authUrl = $service->getAuthUrl($base64EncodedData, $clientId);

        return ['auth_type' => $authType, 'auth_url' => $authUrl, 'token' => null];

    }

    public function handleAuthCallback(array $request): array
    {
        $this->validateAuthCallbackRequest($request);

        $state = Arr::get($request, 'state');
        $decryptedState = decodeAndDecrypt($state);

        $credentialUid = Arr::get($decryptedState, 'credential_uid');
        $shopId = Arr::get($decryptedState, 'shop_id');

        $credential = $this->getCredentialByUidShopId($credentialUid, $shopId);

        $tokenResponse = $this->getAccessTokenFromCode(Arr::get($request, 'code'), $credential);

        $refreshToken = $this->getRefreshToken($tokenResponse);

        $scope = $this->getScopes($tokenResponse);
        $hashedScopes = $this->getHashedScopes($tokenResponse);

        if (!is_object($tokenResponse) || !property_exists($tokenResponse, 'access_token')) {
            throw new \Exception('Access token not found or invalid response structure');
        }

        $service = CredentialFactory::make($credential->app->pointer);
        $metaData = $service->getMetaData($tokenResponse->access_token);
        if (!$metaData) {
            throw new \Exception('Meta data not found');
        }

        $dataToStore = [
            'id' => $credential->id,
            'shop_id' => $shopId,
            'status' => CredentialStatus::ACTIVE->value,
            'scopes' => $scope,
            'scope_hash' => $hashedScopes,
            'new_secrets' => [
                'access_token' => $tokenResponse->access_token,
                'refresh_token' => $refreshToken,
                'meta_data' => $metaData,
                'expires_in' => $tokenResponse->expires_in
            ]
        ];

        $this->updateCredentialSecrets($dataToStore);

        $shop = Shop::find($shopId);

        $url = generateTaskUrl($shop, $state);

        return ['url' => $url];

    }

    private function getHashedScopes($tokenResponse): ?string
    {
        if (property_exists($tokenResponse, 'scope') && !empty($tokenResponse->scope)) {
            return Hash::make($tokenResponse->scope);
        }

        return null;
    }

    private function getScopes($tokenResponse): ?string
    {
        return property_exists($tokenResponse, 'scope')
            ? $tokenResponse->scope
            : null;
    }

    private function getRefreshToken($tokenResponse): ?string
    {
        return property_exists($tokenResponse, 'refresh_token')
            ? $tokenResponse->refresh_token
            : '';
    }

    private function validateAuthCallbackRequest(array $request): void
    {
        if (!Arr::has($request, 'code')) {
            throw new \Exception('Authorization code not found');
        }
        if (!Arr::has($request, 'state')) {
            throw new \Exception('Authorization state not found');
        }
    }

    public function credentials(array $request = []): array
    {
        $authType = Arr::get($request, 'auth_type'); // Can be 'mailchimp', 'activecampaign', etc.
        $actionUid = Arr::get($request, 'action_uid');
        $eventUid = Arr::get($request, 'event_uid');
        $triggerUid = Arr::get($request, 'trigger_uid');
        $shopId = shop()->id;

        $app = $this->appService->getAppByUid($actionUid);

        // Resolve the correct credential service based on auth_type
        $credentialService = CredentialFactory::make($app->pointer);

        // Service-specific credential data preparation
        $credentialData = $credentialService->prepareCredentialData($request, $shopId, $app);

        // Store the credentials using the service
        $credential = $this->storeCredentials(new CredentialDTO($credentialData));

        // Generate encrypted token to return
        $encryptEncodedData = encryptAndEncode([
            'credential_uid' => $credential->uid,
            'shop_id' => $shopId,
            'event_uid' => $eventUid,
            'trigger_uid' => $triggerUid,
        ]);

        return ['auth_type' => $authType, 'auth_url' => null, 'token' => $encryptEncodedData];
    }

    public function updateCredentialSecrets(array $data): Credential
    {
        $credential = Credential::where('id', $data['id'])
            ->where('shop_id', $data['shop_id'])
            ->first();

        if (!$credential) {
            throw new \Exception('Credential not found');
        }

        $credential->secrets = array_replace($credential->secrets, $data['new_secrets']);
        $credential->scopes = $data['scopes'];
        $credential->scope_hash = $data['scope_hash'];
        $credential->status = $data['status'];
        $credential->save();
        return $credential;
    }

    public function getCredentialByUidShopId($uid, $shopId): ?Model
    {
        return Credential::with(['app' => function ($query) {
            $query->select('id', 'uid', 'pointer');
        }])->where('uid', $uid)
            ->where('shop_id', $shopId)
            ->first();
    }

    public function getCredentialByUid($uid): ?Model
    {
        return Credential::with(['app' => function ($query) {
            $query->select('id', 'uid', 'pointer');
        }])->where('uid', $uid)
            ->first();
    }

    public function getClientCredentials(Credential $credential): ?array
    {
        $service = CredentialFactory::make($credential->app->pointer);
        $clientId = $service->getClientId();
        $clientSecret = $service->getClientSecret();
        $authApiTokenEndpoint = $service->getAuthApiTokenEndpoint();
        $oauthCallback = $service->getOauthCallback();

        if ($credential->source === AuthType::CLIENT_SECRET->value) {
            $clientId = Arr::get($credential->secrets, 'client_id');
            $clientSecret = Arr::get($credential->secrets, 'client_secret');
            $oauthCallback = Arr::get($credential->secrets, 'redirect_uri');
        }

        return [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'oauthCallback' => $oauthCallback,
            'authApiEndPoint' => $authApiTokenEndpoint,
        ];
    }

    public function getAccessTokenFromCode($code, $credential = null)
    {
        $clientCredentials = $this->getClientCredentials($credential);
        $clientId = $clientCredentials['clientId'];
        $clientSecret = $clientCredentials['clientSecret'];
        $oauthCallback = $clientCredentials['oauthCallback'];
        $authApiTokenEndpoint = $clientCredentials['authApiEndPoint'];

        $response = Http::asForm()->post($authApiTokenEndpoint, [
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $oauthCallback,
            'code' => $code,
        ]);
        return json_decode($response->body());
    }

    public function softDeleteByUid(string $uid): bool
    {
        $credential = Credential::where('uid', $uid)->firstOrFail();
        return $credential->delete();
    }
}
