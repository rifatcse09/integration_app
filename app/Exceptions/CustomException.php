<?php

namespace App\Exceptions;

class CustomException extends \Exception
{

    public function __construct(string $message = "", protected  array $errorPayload = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getErrorPayload() :array
    {
        return $this->errorPayload;
    }




}
