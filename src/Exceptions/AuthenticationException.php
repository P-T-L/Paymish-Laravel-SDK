<?php

namespace Bhekor\Paymish\Exceptions;

class AuthenticationException extends PaymishException
{
    public function __construct(string $message = "Authentication failed", int $code = 401)
    {
        parent::__construct($message, $code);
    }
}
