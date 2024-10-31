<?php

namespace App\Integrations\GoogleSheet;

use App\Enums\AuthType;
use App\Integrations\MailChimp\MailChimpClient;
use App\Models\Credential;
use App\Services\CredentialService;
use App\Traits\AuthMethods;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\ConnectionException as ConnectionExceptionAlias;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Mockery\Exception;
use stdClass;

class GoogleSheetCredentialService
{
    use AuthMethods;

    public string $authApiEndpoint;
    public string $authApiTokenEndpoint;
    public mixed $clientId;
    public mixed $clientSecret;
    public string $oauthCallback;
    public string $metaDataUrl;
    public ?string $accessToken = null;
    public string $scope;

    public function __construct(protected CredentialService $credentialService)
    {

        $this->authApiEndpoint = config('integration.services.google_sheet.authApiEndpoint');
        $this->clientId = config('integration.services.google_sheet.clientId');
        $this->clientSecret = config('integration.services.google_sheet.clientSecret');
        $this->authApiTokenEndpoint = config('integration.services.google_sheet.authApiTokenEndpoint');
        $this->metaDataUrl = config('integration.services.google_sheet.apiMetaDataEndpoint');
        $this->scope = config('integration.services.google_sheet.scope');
        $this->oauthCallback = route('integration.callback', ['google_sheet']);

    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getAuthApiTokenEndpoint(): string
    {
        return $this->authApiTokenEndpoint;
    }

    public function getOauthCallback(): string
    {
        return $this->oauthCallback;
    }

    public function getSupportedAuthMethods(): array
    {
        return [
            $this->getOAuth2(),
            $this->getClientToken(),
        ];
    }

    public function retrieveOauthCredentials(array $request): array
    {
        $this->clientSecret = Arr::get($request, 'client_secret', $this->clientSecret);
        $this->clientId = Arr::get($request, 'client_id', $this->clientId);

        if (empty($this->clientSecret) || empty($this->clientId)) {
            throw new Exception('Credentials must be set');
        }
        return ['clientId' => $this->clientId, 'clientSecret' => $this->clientSecret ,'oauthCallback' => $this->oauthCallback ];
    }

    /**
     * Generate the authorization URL for OAuth2.
     *
     * @param string $base64EncodedData Base64 encoded data for state parameter.
     * @param string $clientId The client ID for OAuth2.
     * @return string The constructed authorization URL.
     */
    public function getAuthUrl(string $base64EncodedData, string $clientId): string
    {
        $params = array(
            "response_type" => "code",
            "client_id" => $clientId,
            "redirect_uri" =>  $this->oauthCallback,
            "scope" => $this->scope,
            'state' => $base64EncodedData,
            'access_type' => 'offline',
            'prompt' => 'consent'
        );

       return $this->authApiEndpoint . '?' . http_build_query($params);
    }

    /**
     * @param string $accessToken
     * @return stdClass|null
     * @throws ConnectionExceptionAlias
     */
    public function getMetaData(string $accessToken): ?stdClass
    {
        $response = Http::withToken($accessToken)->get($this->metaDataUrl);

        $metadata = json_decode($response->body());

        return $metadata ?? null;
    }

    /**
     * Get the access token for the specified credential.
     *
     * @param int|string $credentialId The ID of the credential.
     * @return string The access token.
     * @throws ModelNotFoundException If the credential is not found.
     * @throws \InvalidArgumentException|\Exception If the refresh token is invalid or an error occurs during the API request.
     */
    public function getAccessToken(int|string $credentialId): string
    {
        $credential = Credential::findOrFail($credentialId);
        $credentialSecrets = $credential->secrets;

        $this->setClientCredentials($credential, $credentialSecrets);

        $this->validateRefreshToken($credentialSecrets);


        if (!$this->isTokenExpired($credential)) {

            return Arr::get($credentialSecrets, 'access_token');

        }

        return $this->refreshAccessToken($credential);

    }
    /**
     * Refresh the access token using the provided credential.
     *
     * @param object $credential The credential object containing secrets for refreshing the token.
     * @return string The newly refreshed access token.
     * @throws \InvalidArgumentException|ConnectionExceptionAlias If an error occurs during the API request.
     */
    private function refreshAccessToken(object $credential): string
    {
        // Prepare API request parameters
        $credentialSecrets = $credential->secrets;
        $apiEndpoint = config('integration.services.google_sheet.authApiTokenEndpoint');
        $requestParams = [
            'grant_type'    => 'refresh_token',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => Arr::get($credentialSecrets, 'refresh_token'),
        ];
        // Make the API request
        $apiResponse = Http::asForm()->post($apiEndpoint, $requestParams);

        if ($apiResponse->failed() || $apiResponse->json('error')) {
            throw new \InvalidArgumentException('API error occurred during token refresh');
        }

        // Update token details
        $accessToken = $apiResponse->json('access_token');
        $refreshToken = $apiResponse->json('refresh_token');
        $expiresIn = $apiResponse->json('expires_in');

        // Update credential secrets and save
        $credentialSecrets['access_token'] = $accessToken;
        if ($refreshToken) {
            $credentialSecrets['refresh_token'] = $refreshToken;
        }
        $credentialSecrets['expires_in'] = $expiresIn;
        $credential->secrets = $credentialSecrets;
        $credential->save();

        // Return the new token details
        return  $accessToken;
    }

    /**
     * Set the client credentials based on the provided credential and its secrets.
     *
     * @param object $credential The credential object containing source information.
     * @param array $credentialSecrets The secrets associated with the credential.
     * @throws \InvalidArgumentException If the client ID or secret is missing.
     */
    private function setClientCredentials(object $credential, array $credentialSecrets): void
    {
        $source = $credential->source;

        if ($source === AuthType::CLIENT_SECRET->value) {
            if (!Arr::get($credentialSecrets, 'client_id') || !Arr::get($credentialSecrets, 'client_secret')) {
                throw new \InvalidArgumentException('Client ID or secret is missing');
            }

            $this->clientId = $credentialSecrets['client_id'];
            $this->clientSecret = $credentialSecrets['client_secret'];
        } else {
            $this->clientId = config('integration.services.google_sheet.clientId');
            $this->clientSecret = config('integration.services.google_sheet.clientSecret');
        }
    }

    private function validateRefreshToken(array $credentialSecrets): void
    {
        if (empty(Arr::get($credentialSecrets, 'refresh_token'))) {
            throw new \Exception('No refresh token found');
        }
    }

    private function isTokenExpired(object $credential): bool
    {
        $updatedAt = $credential->updated_at->getTimestamp();
        $expiresIn = $credential->expires_in;
        $expiryTime = $updatedAt + $expiresIn;

        return $expiryTime < time();
    }

}
