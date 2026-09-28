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
     * Get a specific value from the response
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
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
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset): void
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
