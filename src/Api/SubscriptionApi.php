<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class SubscriptionApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve a list of subscriptions
     *
     * @param array $filters Filters including page, limit, name, status, amount, expiry dates, sort, order
     * @return ApiResponse
     */
    public function list(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('subscription', 'list');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Create a new subscription
     *
     * @param array $data Subscription data
     * @return ApiResponse
     */
    public function create(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('subscription', 'create');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Get a subscription by ID
     *
     * @param int $id The subscription ID
     * @return ApiResponse
     */
    public function get(int $id): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('subscription', 'get');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->get($endpoint);
    }

    /**
     * Update a subscription
     *
     * @param int $id The subscription ID
     * @param array $data Update data
     * @return ApiResponse
     */
    public function update(int $id, array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('subscription', 'update');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->put($endpoint, $data);
    }

    /**
     * Delete a subscription
     *
     * @param int $id The subscription ID
     * @return ApiResponse
     */
    public function delete(int $id): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('subscription', 'delete');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->delete($endpoint);
    }
}
