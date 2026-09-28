<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class SplitPageApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new split page
     *
     * @param array $data Split page data
     * @return ApiResponse
     */
    public function create(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('split_page', 'create');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Returns the list of all split pages
     *
     * @param array $filters Filters including page, limit, searchString, sort, status, splitType, category
     * @return ApiResponse
     */
    public function list(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('split_page', 'list');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Get a split page by id
     *
     * @param int $id The split page id
     * @return ApiResponse
     */
    public function get(int $id): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('split_page', 'get');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->get($endpoint);
    }

    /**
     * Update a split page by ID
     *
     * @param int $id The split page ID
     * @param array $data Update data
     * @return ApiResponse
     */
    public function update(int $id, array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('split_page', 'update');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->put($endpoint, $data);
    }

    /**
     * Remove a split page by ID
     *
     * @param int $id The split page ID
     * @return ApiResponse
     */
    public function delete(int $id): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('split_page', 'delete');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->delete($endpoint);
    }

    /**
     * Add a subaccount to a split page
     *
     * @param int $id The split page id
     * @param array $data Data including shere and sub_account_id
     * @return ApiResponse
     */
    public function addSubaccount(int $id, array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('split_page', 'add_subaccount');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->post($endpoint, $data);
    }

    /**
     * Update a subaccount in a split page
     *
     * @param int $id The split page id
     * @param array $data Data including shere and sub_account_id
     * @return ApiResponse
     */
    public function updateSubaccount(int $id, array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('split_page', 'update_subaccount');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->put($endpoint, $data);
    }

    /**
     * Delete a subaccount from a split page
     *
     * @param int $id The split page id
     * @return ApiResponse
     */
    public function deleteSubaccount(int $id): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('split_page', 'delete_subaccount');
        $endpoint = $this->client->buildEndpoint($endpoint, ['id' => $id]);
        return $this->client->delete($endpoint);
    }
}
