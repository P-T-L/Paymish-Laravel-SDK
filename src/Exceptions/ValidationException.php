<?php

namespace Bhekor\Paymish\Exceptions;

class ValidationException extends PaymishException
{
    public function __construct(string $message = "Validation failed", array $errors = [], int $code = 400)
    {
        parent::__construct($message, $code, $errors);
    }
}
