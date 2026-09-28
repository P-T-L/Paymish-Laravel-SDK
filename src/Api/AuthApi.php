<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class AuthApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Generate authentication token
     *
     * @param array $credentials Login credentials (email, password, etc.)
     * @return ApiResponse
     */
    public function generateToken(array $credentials): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('auth', 'generate_token');
        $response = $this->client->post($endpoint, $credentials);

        // Store the token if present in response
        if ($response->has('token') || $response->has('access_token')) {
            $token = $response->get('token') ?? $response->get('access_token');
            $this->client->setBearerToken($token);
        }

        return $response;
    }
}
