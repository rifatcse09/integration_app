<?php

namespace App\Enums;

enum AuthType: string
{
    use EnumTrait;

    case OAUTH2 = 'oauth2';
    case CLIENT_SECRET = 'client_secret';
    case OAUTH1 = 'oauth1';
    case TOKEN = 'token';
    case BASIC_AUTH = 'basic_auth';
    case API_KEY = 'api_key';
    case OTHER_AUTH = 'other_auth';

    public static function getTitle(string $authType): string
    {
        return match($authType) {
            self::OAUTH2->value => 'OAuth 2.0 Authentication',
            self::CLIENT_SECRET->value => 'Client Secret Authentication',
            self::OAUTH1->value => 'OAuth 1.0 Authentication',
            self::TOKEN->value => 'Token-Based Authentication',
            self::BASIC_AUTH->value => 'Basic Authentication',
            self::API_KEY->value => 'API Key Authentication',
            self::OTHER_AUTH->value => 'Other Authentication',
            default => 'Unknown Authentication Type',
        };
    }

}
