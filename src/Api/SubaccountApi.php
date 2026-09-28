<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class SubaccountApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new subaccount
     *
     * @param array $data Subaccount data (business_name, account_number, percentage_charge, currency, bank, account_name)
     * @return ApiResponse
     */
    public function create(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('subaccount', 'create');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Get all subaccounts
     *
     * @param array $filters Filters including page, limit, searchString, sort, sortKey
     * @return ApiResponse
     */
    public function list(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('subaccount', 'list');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Get a subaccount by ID
     *
     * @param int $id The subaccount ID
     * @return ApiResponse
     */
    public function get(int $id): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('subaccount', 'get');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->get($endpoint);
    }

    /**
     * Update a subaccount by ID
     *
     * @param int $id The subaccount ID
     * @param array $data Update data
     * @return ApiResponse
     */
    public function update(int $id, array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('subaccount', 'update');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->put($endpoint, $data);
    }

    /**
     * Delete a subaccount by ID
     *
     * @param int $id The subaccount ID
     * @return ApiResponse
     */
    public function delete(int $id): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('subaccount', 'delete');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->delete($endpoint);
    }
}
