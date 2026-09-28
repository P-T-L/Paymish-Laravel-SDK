<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class PlanApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new plan
     *
     * @param array $data Plan data
     * @return ApiResponse
     */
    public function create(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('plan', 'create');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Get payment plans
     *
     * @param array $filters Filters including status, interval, amount, searchPlanText, dates, limit, page
     * @return ApiResponse
     */
    public function list(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('plan', 'list');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Get plan details
     *
     * @param int $planId ID of the plan
     * @return ApiResponse
     */
    public function getDetails(int $planId): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('plan', 'details');
        return $this->client->get($endpoint, [
            'planId' => $planId,
        ]);
    }

    /**
     * Update plan details
     *
     * @param array $data Update data
     * @return ApiResponse
     */
    public function updateDetails(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('plan', 'update');
        return $this->client->put($endpoint, $data);
    }

    /**
     * Delete a plan
     *
     * @param array $data Data including plan ID
     * @return ApiResponse
     */
    public function delete(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('plan', 'delete');
        return $this->client->post($endpoint, $data);
    }
}
