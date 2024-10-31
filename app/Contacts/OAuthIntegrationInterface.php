<?php

namespace App\Contacts;

interface OAuthIntegrationInterface
{
    public function oAuth(array $request = []): array;

    public function handleAuthCallback(array $request);

}
