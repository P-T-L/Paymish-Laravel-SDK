<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class DedicatedVirtualAccountApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a dedicated virtual account (GTBank via Squad per Paymish docs).
     *
     * Required: customerId, firstName, lastName, email, phoneNumber, bvn, dob, address, gender.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('dedicated_virtual_accounts', 'create');

        return $this->client->post($endpoint, $data);
    }
}
