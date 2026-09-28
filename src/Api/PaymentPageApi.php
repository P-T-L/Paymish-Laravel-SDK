<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class PaymentPageApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Check if a custom link exists
     *
     * @param array $data Data including custom link
     * @return ApiResponse
     */
    public function checkCustomLink(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('payment_page', 'check_custom_link');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Create a new payment page
     *
     * @param array $data Payment page data (can include file uploads)
     * @return ApiResponse
     */
    public function create(array $data): ApiResponse
    {
        $multipart = [];

        foreach ($data as $key => $value) {
            if ($key === 'seoImage' && is_file($value)) {
                $multipart[] = [
                    'name' => $key,
                    'contents' => fopen($value, 'r'),
                    'filename' => basename($value),
                ];
            } elseif (is_array($value)) {
                foreach ($value as $item) {
                    $multipart[] = [
                        'name' => $key . '[]',
                        'contents' => $item,
                    ];
                }
            } else {
                $multipart[] = [
                    'name' => $key,
                    'contents' => $value,
                ];
            }
        }

        $endpoint = $this->client->getEndpoint('payment_page', 'create');
        return $this->client->multipart($endpoint, $multipart);
    }

    /**
     * Get payment pages
     *
     * @param array $filters Filters including status, searchPageText, startDate, endDate, page, limit, sort
     * @return ApiResponse
     */
    public function list(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('payment_page', 'list');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Get payment page details
     *
     * @param int $pageId The payment page ID
     * @return ApiResponse
     */
    public function getDetails(int $pageId): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('payment_page', 'details');
        return $this->client->get($endpoint, [
            'pageId' => $pageId,
        ]);
    }

    /**
     * Update page details
     *
     * @param array $data Update data (can include file uploads)
     * @return ApiResponse
     */
    public function updateDetails(array $data): ApiResponse
    {
        $multipart = [];

        foreach ($data as $key => $value) {
            if ($key === 'seoImage' && is_file($value)) {
                $multipart[] = [
                    'name' => $key,
                    'contents' => fopen($value, 'r'),
                    'filename' => basename($value),
                ];
            } elseif (is_array($value)) {
                foreach ($value as $item) {
                    $multipart[] = [
                        'name' => $key . '[]',
                        'contents' => $item,
                    ];
                }
            } else {
                $multipart[] = [
                    'name' => $key,
                    'contents' => $value,
                ];
            }
        }

        $endpoint = $this->client->getEndpoint('payment_page', 'update_details');
        return $this->client->multipart($endpoint, $multipart);
    }

    /**
     * Update page status
     *
     * @param array $data Data including pageId and status
     * @return ApiResponse
     */
    public function updateStatus(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('payment_page', 'update_status');
        return $this->client->put($endpoint, $data);
    }

    /**
     * Delete a page
     *
     * @param array $data Data including page ID
     * @return ApiResponse
     */
    public function delete(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('payment_page', 'delete');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Retrieve products associated with a payment page
     *
     * @param int $pageId The payment page ID
     * @param string|null $searchProduct Search query for products
     * @return ApiResponse
     */
    public function getProducts(int $pageId, ?string $searchProduct = null): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('payment_page', 'get_products');
        return $this->client->get($endpoint, [
            'pageId' => $pageId,
            'searchProduct' => $searchProduct,
        ]);
    }

    /**
     * Add products to a payment page
     *
     * @param array $data Data including pageId and productIds (comma-separated)
     * @return ApiResponse
     */
    public function addProduct(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('payment_page', 'add_product');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Delete a product from a payment page
     *
     * @param array $data Data including paymentPageProductId
     * @return ApiResponse
     */
    public function deleteProduct(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('payment_page', 'delete_product');
        return $this->client->post($endpoint, $data);
    }
}
