<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class TransferApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve the list of banks
     *
     * @return ApiResponse
     */
    public function getAllBanks(): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transfer', 'all_banks');
        return $this->client->get($endpoint);
    }

    /**
     * Initiate a single transfer to a user
     *
     * @param array $data Transfer data (amount, bankCode, bankName, accountNumber, accountName, etc.)
     * @return ApiResponse
     */
    public function createSingle(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transfer', 'create_single');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Retrieve a list of transfers
     *
     * @param array $filters Filters including transferReference, status, limit, page
     * @return ApiResponse
     */
    public function getList(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transfer', 'get_list');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Retrieve the balance
     *
     * @return ApiResponse
     */
    public function getBalance(): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transfer', 'balance');
        return $this->client->get($endpoint);
    }

    /**
     * Retrieve transfer details
     *
     * @param string $transferCode The unique code of the transfer
     * @return ApiResponse
     */
    public function getDetails(string $transferCode): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transfer', 'details');
        return $this->client->get($endpoint, [
            'transferCode' => $transferCode,
        ]);
    }

    /**
     * Submit bulk transfer
     *
     * @param array $data Bulk transfer data
     * @return ApiResponse
     */
    public function submitBulk(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transfer', 'submit_bulk');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Resolve (name enquiry) a bank account number.
     *
     * @param  array{bankCode: string, accountNumber: string}  $data
     */
    public function resolveAccount(array $data): ApiResponse
    {
        return $this->client->transaction()->verifyAccount($data);
    }

    /**
     * Verify a transfer by transfer code (preferred) or list filter by reference.
     */
    public function verifyTransfer(string $referenceOrCode): ApiResponse
    {
        $referenceOrCode = trim($referenceOrCode);
        if ($referenceOrCode === '') {
            return $this->getDetails('');
        }

        if (str_starts_with(strtoupper($referenceOrCode), 'TRF')) {
            return $this->getDetails($referenceOrCode);
        }

        try {
            return $this->getDetails($referenceOrCode);
        } catch (\Throwable) {
            return $this->getList(['transferReference' => $referenceOrCode]);
        }
    }
}
