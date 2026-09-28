<?php

namespace Bhekor\Paymish\Exceptions;

use Exception;

class PaymishException extends Exception
{
    protected array $errors = [];

    public function __construct(string $message = "", int $code = 0, array $errors = [])
    {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    /**
     * Get validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if there are validation errors
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
