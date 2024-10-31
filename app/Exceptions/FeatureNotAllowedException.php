<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class FeatureNotAllowedException extends Exception
{
    public function __construct(string $featureName, string $details = "", int $code = 0, Throwable $previous = null)
    {
        $this->message = "{$featureName} feature not allowed!";

        parent::__construct($details, $code, $previous);
    }
}
