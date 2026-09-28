<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class RecipientApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve a list of recipients
     *
     * @param array $filters Filters including status, searchRecipientAccountName, limit, page
     * @return ApiResponse
     */
    public function list(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('recipient', 'list');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Retrieve details of a specific recipient
     *
     * @param string $recipientCode Code of the recipient
     * @return ApiResponse
     */
    public function getDetails(string $recipientCode): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('recipient', 'details');
        return $this->client->get($endpoint, [
            'recipientCode' => $recipientCode,
        ]);
    }

    /**
     * Delete a recipient
     *
     * @param string $recipientId ID of the recipient to delete
     * @return ApiResponse
     */
    public function delete(string $recipientId): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('recipient', 'delete');
        return $this->client->delete($endpoint, [
            'recipientId' => $recipientId,
        ]);
    }

    /**
     * Blacklist a recipient
     *
     * @param array $data Data including recipientId and isBlackListed
     * @return ApiResponse
     */
    public function blacklist(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('recipient', 'blacklist');
        return $this->client->post($endpoint, $data);
    }
}
