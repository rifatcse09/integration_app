<?php

namespace App\Contacts;

use App\DTOs\CredentialDTO;
use App\Models\Credential;

interface CredentialServiceInterface
{
    public function authorize(array $request): array;

    public function prepareCredentialData(array $request, $shopId, $app): array;

}
