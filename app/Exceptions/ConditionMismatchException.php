<?php

namespace App\Exceptions;

class ConditionMismatchException extends \Exception
{
    public function __construct(string $message = "", protected  array $errorPayload = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getErrorPayload() :array
    {
        return  [
            "errors" => [
                "Conditional Logic False" => [
                    "Conditional Logic not matched"
                ]
            ],
            "error_data" => [
            ]
        ];
    }

}
