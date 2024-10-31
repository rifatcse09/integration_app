<?php

namespace App\Integrations\MailChimp;

use App\Enums\AuthType;
use Illuminate\Support\Arr;
use MailchimpMarketing\ApiClient;

class MailChimpClient
{
    /**
     * Creates and configures a Mailchimp API client instance.
     *
     * @param string $apiKey The API key used for authenticating with the Mailchimp API.
     * @param string $server The server prefix for the Mailchimp API (e.g., "us1", "us2").
     *
     * @return ApiClient Returns a configured instance of the Mailchimp API client.
     */
    protected function createMailchimpClient(string $apiKey, string $server): ApiClient
    {
        $mailchimp = new ApiClient();
        $mailchimp->setConfig([
            'apiKey' => $apiKey,
            'server' => $server
        ]);
        return $mailchimp;
    }

    /**
     * @param string $apiKey
     * @param string $server
     * @return ApiClient
     */
    public function apiEndPoint(string $apiKey, string $server): ApiClient
    {
        return $this->createMailchimpClient($apiKey, $server);
    }

    /**
     * Retrieves the access token or API key from the given credential.
     *
     * @param object $credential The credential object containing the source and secrets.
     *
     * @return string|null Returns the access token if available, or the API key if the source is API_KEY; otherwise, returns null.
     */
    public function getAccessToken(object $credential): ?string
    {
        return match ($credential->source) {
            AuthType::OAUTH2->value, AuthType::CLIENT_SECRET->value => Arr::get($credential->secrets, 'access_token'),
            AuthType::API_KEY->value => Arr::get($credential->secrets, 'api_key'),
            default => null,
        };
    }

    /**
     * Retrieve the data center associated with the given credential.
     *
     * @param mixed $credential The credential object containing metadata.
     * @param string $apiKey The API key used to determine the data center.
     *
     * @return string|null Returns the data center string or null if not found.
     */
    public function getDataCenter(mixed $credential, string $apiKey): ?string
    {
        if (AuthType::API_KEY->value === $credential->source) {
            return substr($apiKey, strpos($apiKey, '-') + 1);
        }
        return $credential->secrets['meta_data']['dc'] ?? null;
    }
}

