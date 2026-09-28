<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class BusinessApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Change the mode (live or not live) of a specific business
     *
     * @param bool $isLive The new mode (true for live, false for test)
     * @return ApiResponse
     */
    public function changeMode(bool $isLive): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('business', 'change_mode');
        return $this->client->post($endpoint, [
            'isLive' => $isLive,
        ]);
    }

    /**
     * Retrieve detailed information about the current business
     *
     * @return ApiResponse
     */
    public function getDetails(): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('business', 'details');
        return $this->client->get($endpoint);
    }

    /**
     * Add a new business
     *
     * @param array $data Business data (countryId, businessName, businessCategory, businessDescription)
     * @return ApiResponse
     */
    public function create(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('business', 'create');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Retrieve a list of all businesses associated with the user
     *
     * @return ApiResponse
     */
    public function list(): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('business', 'list');
        return $this->client->get($endpoint);
    }
}
