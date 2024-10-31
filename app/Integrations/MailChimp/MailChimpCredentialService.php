<?php

namespace App\Integrations\MailChimp;

use App\Contacts\CredentialServiceInterface;
use App\Enums\AuthType;
use App\Enums\CredentialStatus;
use App\Services\CredentialService;
use App\Traits\AuthMethods;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Mockery\Exception;
use stdClass;

class MailChimpCredentialService implements CredentialServiceInterface
{

    use AuthMethods;

    public string $authApiEndpoint;
    public string $authApiTokenEndpoint;
    public mixed $clientId;
    public mixed $clientSecret;
    public string $oauthCallback;
    public string $metaDataUrl;
    public ?string $accessToken = null;

    public function __construct(protected MailChimpClient $mailchimpClient, protected CredentialService $credentialService)
    {

        $this->authApiEndpoint = config('integration.services.mail_chimp.authApiEndpoint');
        $this->clientId = config('integration.services.mail_chimp.clientId');
        $this->clientSecret = config('integration.services.mail_chimp.clientSecret');
        $this->authApiTokenEndpoint = config('integration.services.mail_chimp.authApiTokenEndpoint');
        $this->metaDataUrl = config('integration.services.mail_chimp.apiMetaDataEndpoint');
        $this->oauthCallback = route('integration.callback', ['mail_chimp']);

    }

    public function getClientId():string
    {
        return $this->clientId;
    }

    public function getClientSecret():string
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
            $this->getApiKeyAuth()
        ];
    }

    public function authorize(array $request): array
    {

        $apiKey = Arr::get($request, 'api_key');
        $dc = substr($apiKey, strpos($apiKey, '-') + 1);

        $response = $this->mailchimpClient->apiEndPoint($apiKey, $dc)->ping->get();

        if (isset($response->health_status) && $response->health_status === "Everything's Chimpy!") {
            return [
                'status' => true,
                'dc' => $dc
            ];
        }

        return [
            'status' => false,  // Indicates failure
            'dc' => null
        ];


    }

    public function prepareCredentialData(array $request, $shopId, $app): array
    {
        $apiKey = Arr::get($request, 'api_key');
        $response = $this->authorize($request);
        if (!$response['status']) {
            throw  new \Exception('Authentication failed');
        }

        return [
            'name' => $request['auth_type'],
            'shop_id' => $shopId,
            'secrets' => [
                'api_key' => $apiKey,
                'dc' => $response['dc'] // Data center specific to Mailchimp
            ],
            'source' => $request['auth_type'],
            'status' => CredentialStatus::ACTIVE->value,
            'app_id' => $app->id,
        ];
    }

    public function retrieveOauthCredentials(array $request): array
    {
        $this->clientSecret = Arr::get($request, 'client_secret', $this->clientSecret);
        $this->clientId = Arr::get($request, 'client_id', $this->clientId);
        $this->oauthCallback = Arr::get($request, 'redirect_url', $this->oauthCallback);

        if (empty($this->clientSecret) || empty($this->clientId)) {
            throw new Exception('Credentials must be set');
        }
        return ['clientId' => $this->clientId, 'clientSecret' => $this->clientSecret, 'oauthCallback' => $this->oauthCallback];
    }

    public function getAuthUrl(string $base64EncodedData, string $clientId): string
    {

        return URL::to($this->authApiEndpoint . '?' . http_build_query([
                'response_type' => 'code',
                'client_id' => $clientId,
                'state' => $base64EncodedData,
                'redirect_uri' => $this->oauthCallback,
            ]));
    }

    public function getMetaData(string $accessToken): ?stdClass
    {
        $response = Http::withHeaders([
            'Authorization' => 'OAuth ' . $accessToken,
        ])->get($this->metaDataUrl);

        $metadata = json_decode($response->body());

        return $metadata ?? null;
    }
}
