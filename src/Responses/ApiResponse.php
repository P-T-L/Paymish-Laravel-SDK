<?php

namespace Bhekor\Paymish\Responses;

use ArrayAccess;
use JsonSerializable;

class ApiResponse implements ArrayAccess, JsonSerializable
{
    protected int $statusCode;
    protected array $data;

    public function __construct(int $statusCode, array $data = [])
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    /**
     * Get the HTTP status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Check if the request was successful
     */
    public function successful(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * Get all response data
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Get a specific value from the response (supports dot notation)
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (! str_contains($key, '.')) {
            return $default;
        }

        $segments = explode('.', $key);
        $value = $this->data;

        foreach ($segments as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Get the data key from response
     */
    public function data($default = null)
    {
        return $this->get('data', $default);
    }

    /**
     * Get the message from response
     */
    public function message(): ?string
    {
        return $this->get('message');
    }

    /**
     * Check if a key exists in the response
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * ArrayAccess implementation
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    /**
     * JsonSerializable implementation
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    /**
     * Convert response to array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Convert response to JSON
     */
    public function toJson(): string
    {
        return json_encode($this->data);
    }

    /**
     * Get response as string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
