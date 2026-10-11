<?php

namespace Bhekor\Paymish;

use Bhekor\Paymish\Api\AuthApi;
use Bhekor\Paymish\Api\BusinessApi;
use Bhekor\Paymish\Api\CustomerApi;
use Bhekor\Paymish\Api\DedicatedVirtualAccountApi;
use Bhekor\Paymish\Api\DisputeApi;
use Bhekor\Paymish\Api\PaymentPageApi;
use Bhekor\Paymish\Api\PlanApi;
use Bhekor\Paymish\Api\ProductApi;
use Bhekor\Paymish\Api\RecipientApi;
use Bhekor\Paymish\Api\SplitPageApi;
use Bhekor\Paymish\Api\SubaccountApi;
use Bhekor\Paymish\Api\SubscriptionApi;
use Bhekor\Paymish\Api\TransactionApi;
use Bhekor\Paymish\Api\TransferApi;
use Bhekor\Paymish\Api\VirtualAccountApi;
use Bhekor\Paymish\Exceptions\AuthenticationException;
use Bhekor\Paymish\Exceptions\PaymishException;
use Bhekor\Paymish\Exceptions\ValidationException;
use Bhekor\Paymish\Responses\ApiResponse;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class PaymishClient
{
    protected GuzzleClient $httpClient;

    protected ?string $apiKey;

    protected string $baseUrl;

    protected int $timeout;

    protected bool $isLive;

    protected ?string $bearerToken = null;

    protected array $endpoints;

    protected ?string $publicKey;

    protected ?string $secretKey;

    protected ?string $email;

    protected ?string $password;

    protected int $tokenCacheSeconds;

    protected bool $skipAutoAuth = false;

    public function __construct(
        ?string $apiKey = null,
        string $baseUrl = 'https://api.paymish.com',
        int $timeout = 30,
        bool $isLive = false,
        ?array $endpoints = null,
        ?string $publicKey = null,
        ?string $secretKey = null,
        ?string $email = null,
        ?string $password = null,
        int $tokenCacheSeconds = 3500,
    ) {
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
        $this->isLive = $isLive;
        $this->endpoints = $endpoints ?? $this->loadDefaultEndpoints();
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
        $this->email = $email;
        $this->password = $password;
        $this->tokenCacheSeconds = max(60, $tokenCacheSeconds);

        $this->httpClient = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    protected function loadDefaultEndpoints(): array
    {
        try {
            if (function_exists('app') && app()->bound('config')) {
                $endpoints = config('paymish.endpoints');
                if (is_array($endpoints) && $endpoints !== []) {
                    return $endpoints;
                }
            }
        } catch (\Throwable) {
            // Outside a bootstrapped Laravel application.
        }

        $configFile = __DIR__.'/config/endpoints.php';

        if (file_exists($configFile)) {
            return require $configFile;
        }

        return [];
    }

    public function getEndpoint(string $feature, string $key): string
    {
        return $this->endpoints[$feature][$key] ?? '';
    }

    public function getEndpoints(): array
    {
        return $this->endpoints;
    }

    public function setEndpoints(array $endpoints): self
    {
        $this->endpoints = array_merge($this->endpoints, $endpoints);

        return $this;
    }

    public function buildEndpoint(string $endpoint, array $params = []): string
    {
        foreach ($params as $key => $value) {
            $endpoint = str_replace('{'.$key.'}', (string) $value, $endpoint);
        }

        return $endpoint;
    }

    public function auth(): AuthApi
    {
        return new AuthApi($this);
    }

    public function dispute(): DisputeApi
    {
        return new DisputeApi($this);
    }

    public function transaction(): TransactionApi
    {
        return new TransactionApi($this);
    }

    public function transfer(): TransferApi
    {
        return new TransferApi($this);
    }

    public function recipient(): RecipientApi
    {
        return new RecipientApi($this);
    }

    public function subscription(): SubscriptionApi
    {
        return new SubscriptionApi($this);
    }

    public function plan(): PlanApi
    {
        return new PlanApi($this);
    }

    public function paymentPage(): PaymentPageApi
    {
        return new PaymentPageApi($this);
    }

    public function product(): ProductApi
    {
        return new ProductApi($this);
    }

    public function customer(): CustomerApi
    {
        return new CustomerApi($this);
    }

    public function business(): BusinessApi
    {
        return new BusinessApi($this);
    }

    public function splitPage(): SplitPageApi
    {
        return new SplitPageApi($this);
    }

    public function subaccount(): SubaccountApi
    {
        return new SubaccountApi($this);
    }

    public function dedicatedVirtualAccount(): DedicatedVirtualAccountApi
    {
        return new DedicatedVirtualAccountApi($this);
    }

    public function virtualAccount(): VirtualAccountApi
    {
        return new VirtualAccountApi($this);
    }

    public function setBearerToken(string $token): self
    {
        $this->bearerToken = $token;

        return $this;
    }

    public function getBearerToken(): ?string
    {
        return $this->bearerToken;
    }

    public function isLive(): bool
    {
        return $this->isLive;
    }

    /**
     * Build credentials for generate-token. Prefer public/secret keys when both set.
     *
     * @return array<string, string>|null
     */
    public function authCredentials(): ?array
    {
        $publicKey = is_string($this->publicKey) ? trim($this->publicKey) : '';
        $secretKey = is_string($this->secretKey) ? trim($this->secretKey) : '';
        if ($publicKey !== '' && $secretKey !== '') {
            return [
                'public_key' => $publicKey,
                'secret_key' => $secretKey,
            ];
        }

        $email = is_string($this->email) ? trim($this->email) : '';
        $password = is_string($this->password) ? (string) $this->password : '';
        if ($email !== '' && $password !== '') {
            return [
                'email' => $email,
                'password' => $password,
            ];
        }

        return null;
    }

    public function ensureAuthenticated(bool $force = false): void
    {
        if (! $force && is_string($this->bearerToken) && $this->bearerToken !== '') {
            return;
        }

        $cacheKey = $this->tokenCacheKey();
        if (! $force && $cacheKey !== null && class_exists(Cache::class)) {
            try {
                $cached = Cache::get($cacheKey);
                if (is_string($cached) && $cached !== '') {
                    $this->bearerToken = $cached;

                    return;
                }
            } catch (\Throwable) {
                // Cache may be unavailable outside Laravel; fall through.
            }
        }

        $credentials = $this->authCredentials();
        if ($credentials === null) {
            throw new AuthenticationException('Paymish credentials are not configured.');
        }

        $this->skipAutoAuth = true;
        try {
            $response = $this->auth()->generateToken($credentials);
        } finally {
            $this->skipAutoAuth = false;
        }

        $token = $this->extractTokenFromResponse($response->toArray());
        if ($token === null || $token === '') {
            throw new AuthenticationException(
                (string) ($response->message() ?? 'Failed to obtain Paymish bearer token')
            );
        }

        $this->setBearerToken($token);

        if ($cacheKey !== null && class_exists(Cache::class)) {
            try {
                Cache::put($cacheKey, $token, $this->tokenCacheSeconds);
            } catch (\Throwable) {
                // ignore
            }
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function extractTokenFromResponse(array $payload): ?string
    {
        $candidates = [
            $payload['token'] ?? null,
            $payload['access_token'] ?? null,
            data_get($payload, 'data.token'),
            data_get($payload, 'data.access_token'),
            data_get($payload, 'data.accessToken'),
        ];

        foreach ($candidates as $candidate) {
            if (is_string($candidate) && $candidate !== '') {
                return $candidate;
            }
        }

        return null;
    }

    protected function tokenCacheKey(): ?string
    {
        $credentials = $this->authCredentials();
        if ($credentials === null) {
            return null;
        }

        return 'paymish.bearer.'.md5($this->baseUrl.'|'.json_encode($credentials));
    }

    public function get(string $endpoint, array $query = [], bool $authenticate = true): ApiResponse
    {
        return $this->request('GET', $endpoint, ['query' => $query], $authenticate);
    }

    public function post(string $endpoint, array $data = [], array $options = [], bool $authenticate = true): ApiResponse
    {
        $options['json'] = $data;

        return $this->request('POST', $endpoint, $options, $authenticate);
    }

    public function put(string $endpoint, array $data = [], array $options = [], bool $authenticate = true): ApiResponse
    {
        $options['json'] = $data;

        return $this->request('PUT', $endpoint, $options, $authenticate);
    }

    public function delete(string $endpoint, array $query = [], bool $authenticate = true): ApiResponse
    {
        return $this->request('DELETE', $endpoint, ['query' => $query], $authenticate);
    }

    public function multipart(string $endpoint, array $multipart = [], bool $authenticate = true): ApiResponse
    {
        return $this->request('POST', $endpoint, ['multipart' => $multipart], $authenticate);
    }

    protected function request(string $method, string $endpoint, array $options = [], bool $authenticate = true): ApiResponse
    {
        if ($authenticate && ! $this->skipAutoAuth) {
            $this->ensureAuthenticated();
        }

        try {
            return $this->send($method, $endpoint, $options);
        } catch (AuthenticationException $e) {
            if (! $authenticate || $this->skipAutoAuth) {
                throw $e;
            }

            $this->bearerToken = null;
            $cacheKey = $this->tokenCacheKey();
            if ($cacheKey !== null && class_exists(Cache::class)) {
                try {
                    Cache::forget($cacheKey);
                } catch (\Throwable) {
                    // ignore
                }
            }

            $this->ensureAuthenticated(true);

            return $this->send($method, $endpoint, $options);
        }
    }

    protected function send(string $method, string $endpoint, array $options = []): ApiResponse
    {
        try {
            $headers = $options['headers'] ?? [];

            if ($this->bearerToken) {
                $headers['Authorization'] = 'Bearer '.$this->bearerToken;
            }

            $options['headers'] = $headers;

            $response = $this->httpClient->request($method, $endpoint, $options);

            $decoded = json_decode($response->getBody()->getContents(), true);

            return new ApiResponse(
                $response->getStatusCode(),
                is_array($decoded) ? $decoded : []
            );
        } catch (GuzzleException $e) {
            return $this->handleException($e);
        }
    }

    protected function handleException(GuzzleException $e): ApiResponse
    {
        $statusCode = $e->getCode();
        $response = null;

        if (method_exists($e, 'hasResponse') && $e->hasResponse()) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), true);
            $statusCode = $e->getResponse()->getStatusCode();
        }

        if ($statusCode === 401) {
            throw new AuthenticationException(
                $response['error'] ?? $response['message'] ?? 'Authentication failed',
                $statusCode
            );
        }

        if ($statusCode === 400) {
            throw new ValidationException(
                $response['error'] ?? $response['message'] ?? 'Invalid request data',
                $response['errors'] ?? [],
                $statusCode
            );
        }

        if ($statusCode === 404) {
            throw new PaymishException(
                $response['error'] ?? $response['message'] ?? 'Resource not found',
                $statusCode
            );
        }

        if ($statusCode >= 500) {
            throw new PaymishException(
                $response['error'] ?? $response['message'] ?? 'Server error occurred',
                $statusCode
            );
        }

        throw new PaymishException(
            $response['error'] ?? $response['message'] ?? $e->getMessage(),
            $statusCode
        );
    }
}
