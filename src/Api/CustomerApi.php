<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class CustomerApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new customer
     *
     * @param array $data Customer data
     * @return ApiResponse
     */
    public function create(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('customer', 'create');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Update a customer
     *
     * @param array $data Update data
     * @return ApiResponse
     */
    public function update(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('customer', 'update');
        return $this->client->put($endpoint, $data);
    }

    /**
     * Get customer list
     *
     * @param array $filters Filters including limit, page, search
     * @return ApiResponse
     */
    public function list(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('customer', 'list');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Blacklist or whitelist a customer
     *
     * @param array $data Data including customer ID and blacklist status
     * @return ApiResponse
     */
    public function blacklist(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('customer', 'blacklist');
        return $this->client->put($endpoint, $data);
    }

    /**
     * Get customer details by ID
     *
     * @param int $id The customer ID
     * @return ApiResponse
     */
    public function getById(int $id): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('customer', 'get_by_id');
        return $this->client->get($endpoint, [
            'id' => $id,
        ]);
    }
}
