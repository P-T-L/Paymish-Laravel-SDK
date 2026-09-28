<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class DisputeApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a dispute by uploading a document
     *
     * @param array $data Dispute data including image, transactionRef, disputeReason, disputeDescription
     * @return ApiResponse
     */
    public function create(array $data): ApiResponse
    {
        $multipart = [];

        foreach ($data as $key => $value) {
            if ($key === 'image' && is_file($value)) {
                $multipart[] = [
                    'name' => $key,
                    'contents' => fopen($value, 'r'),
                    'filename' => basename($value),
                ];
            } else {
                $multipart[] = [
                    'name' => $key,
                    'contents' => $value,
                ];
            }
        }

        $endpoint = $this->client->getEndpoint('dispute', 'create');
        return $this->client->multipart($endpoint, $multipart);
    }

    /**
     * Retrieve a list of disputes
     *
     @param array $filters Filters including limit, page, search
     * @return ApiResponse
     */
    public function list(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('dispute', 'list');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Retrieve dispute details by transaction reference
     *
     * @param string $transactionRef The transaction reference
     * @return ApiResponse
     */
    public function getDetails(string $transactionRef): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('dispute', 'details');
        return $this->client->get($endpoint, [
            'transactionRef' => $transactionRef,
        ]);
    }

    /**
     * Update a dispute
     *
     * @param array $data Update data
     * @return ApiResponse
     */
    public function update(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('dispute', 'update');
        return $this->client->put($endpoint, $data);
    }
}
