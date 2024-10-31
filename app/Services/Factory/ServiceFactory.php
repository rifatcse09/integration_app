<?php

namespace App\Services\Factory;


use Illuminate\Support\Str;
use InvalidArgumentException;

class ServiceFactory
{

    public static function getService(string $serviceName): object
    {
        $formatedServiceName = Str::studly($serviceName);
        // Create the class name dynamically
        $className = 'App\\Integrations\\'. $formatedServiceName .'\\' . $formatedServiceName . 'Integration';

        if (!class_exists($className)) {
            throw new InvalidArgumentException("Service class {$className} not found for auth type: {$serviceName}");
        }

        return app($className);

    }
}
