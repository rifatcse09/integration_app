<?php

namespace App\Services\Factory;

use Illuminate\Support\Str;
use InvalidArgumentException;

class CredentialFactory
{
    public static function make(string $serviceName): object
    {
        // Create the class name dynamically
        $formatedServiceName = Str::studly($serviceName);
        $className = 'App\\Integrations\\' . $formatedServiceName .'\\' . $formatedServiceName . 'CredentialService';

        if (!class_exists($className)) {
            throw new InvalidArgumentException("Service class {$className} not found for auth type: {$serviceName}");
        }

        // Resolve the service class dynamically using app() or instantiate it with new
        return app($className);

    }
}
