<?php

namespace App\Contacts;

interface CredentialIntegrationInterface
{
    public function credentials(array $request = []): array;

    public function credentialsAuthorization($credentials): array;
}
