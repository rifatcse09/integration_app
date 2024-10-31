<?php

namespace App\Contacts;

abstract class IntegrationContractService
{
    protected bool $hasSystemOAuth2 = false;

    protected bool $hasClientOAuth2 = false;

    protected bool $hasClientOAuth1 = false;

    protected bool $hasBasicAuth = false;

    protected bool $hasApiKey = false;

    protected bool $hasBearerToken = false;

    protected bool $hasOtherAuth = false;


    protected string $name = 'Integrations';

    protected $config;

//    abstract  protected  function  apiEndPoint(...$requestParts);

    abstract public function getSupportedAuthMethods(): array;

//    abstract public function oAuthRefresh(...$args): void;

//    abstract public function validate(...$args): array;
//
//    abstract public function options(...$args): array;
//
//    abstract public function defaultMapping(string $token): array;

//    abstract public function store(array $request);

//    abstract public function processWebhook(int $webhookRequestId, int $integrationId);

}
