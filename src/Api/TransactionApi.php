<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class TransactionApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Initialize a transaction from your backend
     *
     * @param array $data Transaction initialization data
     * @return ApiResponse
     */
    public function initialize(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'initialize');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Initialize a transaction with Dynamic Virtual Account
     *
     * @param array $data DVA initialization data (email, amount, reference)
     * @return ApiResponse
     */
    public function initializeDva(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'initialize_dva');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Get a list of transactions
     *
     * @param array $filters Filters including reference, status, channel, amount, page, limit, search
     * @return ApiResponse
     */
    public function list(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'list');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Verify a transaction by reference.
     *
     * GET /api/transaction-service/external/v1/verify/{reference}
     */
    public function verify(string $reference): ApiResponse
    {
        $endpoint = $this->client->buildEndpoint(
            $this->client->getEndpoint('transaction', 'verify'),
            ['reference' => $reference]
        );

        return $this->client->get($endpoint);
    }

    /**
     * Verify (name enquiry) a bank account.
     *
     * @param  array{bankCode: string, accountNumber: string}  $data
     */
    public function verifyAccount(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'verify_account');

        return $this->client->post($endpoint, $data);
    }

    /**
     * Process a transaction with card
     *
     * @param array $data Card transaction data
     * @return ApiResponse
     */
    public function withCard(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'with_card');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Create a transaction with NDD (NIBSS Direct Debit)
     *
     * @param array $data NDD transaction data
     * @return ApiResponse
     */
    public function withNdd(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'with_ndd');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Initiate a transaction with QR code (NQR)
     *
     * @param array $data QR transaction data
     * @return ApiResponse
     */
    public function withQr(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'with_qr');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Create a transaction with a bank
     *
     * @param array $data Bank transaction data
     * @return ApiResponse
     */
    public function withBank(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'with_bank');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Create a transaction with USSD
     *
     * @param array $data USSD transaction data
     * @return ApiResponse
     */
    public function withUssd(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'with_ussd');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Verify a transaction with USSD
     *
     * @param array $data Verification data (ussdTransactionId, isLive)
     * @return ApiResponse
     */
    public function verifyUssd(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'verify_ussd');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Verify bank details
     *
     * @param array $data Bank verification data (DestinationInstitutionCode, AccountNumber, isLive)
     * @return ApiResponse
     */
    public function verifyBank(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'bank_verify');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Get the list of banks
     *
     * @param bool $isLive Whether to get live banks
     * @return ApiResponse
     */
    public function getBanks(bool $isLive = false): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'bank_list');
        return $this->client->get($endpoint, [
            'isLive' => $isLive,
        ]);
    }

    /**
     * Refund a transaction
     *
     * @param array $data Refund data (transactionRef, amount)
     * @return ApiResponse
     */
    public function refund(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('transaction', 'refund');
        return $this->client->post($endpoint, $data);
    }
}
