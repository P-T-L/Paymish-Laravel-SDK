<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class VirtualAccountApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('virtual_accounts', 'list');

        return $this->client->get($endpoint, $filters);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('virtual_accounts', 'create');

        return $this->client->post($endpoint, $data);
    }

    public function get(string|int $id): ApiResponse
    {
        $endpoint = $this->client->buildEndpoint(
            $this->client->getEndpoint('virtual_accounts', 'get'),
            ['id' => $id]
        );

        return $this->client->get($endpoint);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(string|int $id, array $data): ApiResponse
    {
        $endpoint = $this->client->buildEndpoint(
            $this->client->getEndpoint('virtual_accounts', 'update'),
            ['id' => $id]
        );

        return $this->client->put($endpoint, $data);
    }
}
