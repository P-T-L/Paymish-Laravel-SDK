<?php

namespace Bhekor\Paymish;

use Bhekor\Paymish\Api\AuthApi;
use Bhekor\Paymish\Api\DisputeApi;
use Bhekor\Paymish\Api\TransactionApi;
use Bhekor\Paymish\Api\TransferApi;
use Bhekor\Paymish\Api\RecipientApi;
use Bhekor\Paymish\Api\SubscriptionApi;
use Bhekor\Paymish\Api\PlanApi;
use Bhekor\Paymish\Api\PaymentPageApi;
use Bhekor\Paymish\Api\ProductApi;
use Bhekor\Paymish\Api\CustomerApi;
use Bhekor\Paymish\Api\BusinessApi;
use Bhekor\Paymish\Api\SplitPageApi;
use Bhekor\Paymish\Api\SubaccountApi;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Bhekor\Paymish\Exceptions\PaymishException;
use Bhekor\Paymish\Exceptions\AuthenticationException;
use Bhekor\Paymish\Exceptions\ValidationException;
use Bhekor\Paymish\Responses\ApiResponse;

class PaymishClient
{
    protected GuzzleClient $httpClient;
    protected ?string $apiKey;
    protected string $baseUrl;
    protected int $timeout;
    protected bool $isLive;
    protected ?string $bearerToken = null;
    protected array $endpoints;

    public function __construct(
        ?string $apiKey = null,
        string $baseUrl = 'https://api.paymish.com',
        int $timeout = 30,
        bool $isLive = false,
        ?array $endpoints = null
    ) {
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
        $this->isLive = $isLive;
        $this->endpoints = $endpoints ?? $this->loadDefaultEndpoints();

        $this->httpClient = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Load default endpoints from configuration file
     */
    protected function loadDefaultEndpoints(): array
    {
        // First try to load from Laravel config if available
        if (function_exists('config') && config('paymish.endpoints')) {
            return config('paymish.endpoints');
        }

        // Fallback to local config file
        $configFile = __DIR__ . '/config/endpoints.php';
        
        if (file_exists($configFile)) {
            return require $configFile;
        }

        return [];
    }

    /**
     * Get endpoint configuration
     */
    public function getEndpoint(string $feature, string $key): string
    {
        return $this->endpoints[$feature][$key] ?? '';
    }

    /**
     * Get all endpoints
     */
    public function getEndpoints(): array
    {
        return $this->endpoints;
    }

    /**
     * Set custom endpoints
     */
    public function setEndpoints(array $endpoints): self
    {
        $this->endpoints = array_merge($this->endpoints, $endpoints);
        return $this;
    }

    /**
     * Replace path parameters in endpoint
     */
    public function buildEndpoint(string $endpoint, array $params = []): string
    {
        foreach ($params as $key => $value) {
            $endpoint = str_replace('{' . $key . '}', $value, $endpoint);
        }
        return $endpoint;
    }

    /**
     * Get the Auth API instance
     */
    public function auth(): AuthApi
    {
        return new AuthApi($this);
    }

    /**
     * Get the Dispute API instance
     */
    public function dispute(): DisputeApi
    {
        return new DisputeApi($this);
    }

    /**
     * Get the Transaction API instance
     */
    public function transaction(): TransactionApi
    {
        return new TransactionApi($this);
    }

    /**
     * Get the Transfer API instance
     */
    public function transfer(): TransferApi
    {
        return new TransferApi($this);
    }

    /**
     * Get the Recipient API instance
     */
    public function recipient(): RecipientApi
    {
        return new RecipientApi($this);
    }

    /**
     * Get the Subscription API instance
     */
    public function subscription(): SubscriptionApi
    {
        return new SubscriptionApi($this);
    }

    /**
     * Get the Plan API instance
     */
    public function plan(): PlanApi
    {
        return new PlanApi($this);
    }

    /**
     * Get the Payment Page API instance
     */
    public function paymentPage(): PaymentPageApi
    {
        return new PaymentPageApi($this);
    }

    /**
     * Get the Product API instance
     */
    public function product(): ProductApi
    {
        return new ProductApi($this);
    }

    /**
     * Get the Customer API instance
     */
    public function customer(): CustomerApi
    {
        return new CustomerApi($this);
    }

    /**
     * Get the Business API instance
     */
    public function business(): BusinessApi
    {
        return new BusinessApi($this);
    }

    /**
     * Get the Split Page API instance
     */
    public function splitPage(): SplitPageApi
    {
        return new SplitPageApi($this);
    }

    /**
     * Get the Subaccount API instance
     */
    public function subaccount(): SubaccountApi
    {
        return new SubaccountApi($this);
    }

    /**
     * Set the bearer token for authentication
     */
    public function setBearerToken(string $token): self
    {
        $this->bearerToken = $token;
        return $this;
    }

    /**
     * Get the current bearer token
     */
    public function getBearerToken(): ?string
    {
        return $this->bearerToken;
    }

    /**
     * Check if the client is in live mode
     */
    public function isLive(): bool
    {
        return $this->isLive;
    }

    /**
     * Make a GET request
     */
    public function get(string $endpoint, array $query = []): ApiResponse
    {
        return $this->request('GET', $endpoint, ['query' => $query]);
    }

    /**
     * Make a POST request
     */
    public function post(string $endpoint, array $data = [], array $options = []): ApiResponse
    {
        $options['json'] = $data;
        return $this->request('POST', $endpoint, $options);
    }

    /**
     * Make a PUT request
     */
    public function put(string $endpoint, array $data = [], array $options = []): ApiResponse
    {
        $options['json'] = $data;
        return $this->request('PUT', $endpoint, $options);
    }

    /**
     * Make a DELETE request
     */
    public function delete(string $endpoint, array $query = []): ApiResponse
    {
        return $this->request('DELETE', $endpoint, ['query' => $query]);
    }

    /**
     * Make a multipart POST request (for file uploads)
     */
    public function multipart(string $endpoint, array $multipart = []): ApiResponse
    {
        return $this->request('POST', $endpoint, ['multipart' => $multipart]);
    }

    /**
     * Make an HTTP request
     */
    protected function request(string $method, string $endpoint, array $options = []): ApiResponse
    {
        try {
            $headers = $options['headers'] ?? [];

            if ($this->bearerToken) {
                $headers['Authorization'] = 'Bearer ' . $this->bearerToken;
            }

            $options['headers'] = $headers;

            $response = $this->httpClient->request($method, $endpoint, $options);

            return new ApiResponse(
                $response->getStatusCode(),
                json_decode($response->getBody()->getContents(), true)
            );
        } catch (GuzzleException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle Guzzle exceptions and convert to Paymish exceptions
     */
    protected function handleException(GuzzleException $e): ApiResponse
    {
        $statusCode = $e->getCode();
        $response = null;

        if ($e->hasResponse()) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), true);
            $statusCode = $e->getResponse()->getStatusCode();
        }

        // Handle authentication errors
        if ($statusCode === 401) {
            throw new AuthenticationException(
                $response['error'] ?? $response['message'] ?? 'Authentication failed',
                $statusCode
            );
        }

        // Handle validation errors
        if ($statusCode === 400) {
            throw new ValidationException(
                $response['error'] ?? $response['message'] ?? 'Invalid request data',
                $response['errors'] ?? [],
                $statusCode
            );
        }

        // Handle not found errors
        if ($statusCode === 404) {
            throw new PaymishException(
                $response['error'] ?? $response['message'] ?? 'Resource not found',
                $statusCode
            );
        }

        // Handle server errors
        if ($statusCode >= 500) {
            throw new PaymishException(
                $response['error'] ?? $response['message'] ?? 'Server error occurred',
                $statusCode
            );
        }

        // Generic exception
        throw new PaymishException(
            $response['error'] ?? $response['message'] ?? $e->getMessage(),
            $statusCode
        );
    }
}
