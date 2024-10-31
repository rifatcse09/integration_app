<?php
namespace App\Traits;

use App\Enums\AuthType;
use Illuminate\Support\Arr;

trait AuthMethods
{
    protected function getOAuth2(): array
    {
        return [
            'type' => AuthType::OAUTH2->value,
            'label' => 'Continue with Bit app',
        ];
    }

    protected function getClientToken(): array
    {
        return [
            'type' => AuthType::CLIENT_SECRET->value,
            'label' => 'Continue with Own app',
            'fields' => [
                [
                    "label" => "Client ID",
                    "name" => "client_id",
                    "type" => "text",
                    "required" => true,
                    "values" => ""
                ],
                [
                    "label" => "Client Secret",
                    "name" => "client_secret",
                    "type" => "text",
                    "required" => true,
                    "values" => ""
                ],
                [
                    "label" => "Redirect URL",
                    "name" => "redirect_url",
                    "type" => "text",
                    "required" => true,
                    "values" => $this->oauthCallback
                ]
            ]
        ];
    }

    protected function getApiKeyAuth(): array
    {
        return [
            'type' => AuthType::API_KEY->value,
            'label' => 'Continue with API Key',
            'fields' => [
                [
                    "label" => "API Key",
                    "name" => "api_key",
                    "type" => "text",
                    "required" => true,
                    "values" => ""
                ]
            ]
        ];
    }

    protected function getApiKeyUrlAuth(): array
    {
        return [
            'type' => AuthType::API_KEY->value,
            'label' => 'Continue with API Key',
            'fields' => [
                [
                    "label" => "API Key",
                    "name" => "api_key",
                    "type" => "text",
                    "required" => true,
                    "values" => ""
                ],
                [
                    "label" => "API URL",
                    "name" => "api_url",
                    "type" => "text",
                    "required" => true,
                    "values" => ""
                ]
            ]
        ];
    }
}
