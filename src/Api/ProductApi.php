<?php

namespace Bhekor\Paymish\Api;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Responses\ApiResponse;

class ProductApi
{
    protected PaymishClient $client;

    public function __construct(PaymishClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new product
     *
     * @param array $data Product data
     * @return ApiResponse
     */
    public function create(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'create');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Get payment products
     *
     * @param array $filters Filters including status, quantityType, productPrice, searchPlanText, dates
     * @return ApiResponse
     */
    public function list(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'list');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Get product details
     *
     * @param string $productId The product ID
     * @return ApiResponse
     */
    public function getDetails(string $productId): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'details');
        return $this->client->get($endpoint, [
            'productId' => $productId,
        ]);
    }

    /**
     * Delete a product
     *
     * @param array $data Data including product ID
     * @return ApiResponse
     */
    public function delete(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'delete');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Archive a product
     *
     * @param array $data Data including product ID
     * @return ApiResponse
     */
    public function archive(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'archive');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Update product details
     *
     * @param array $data Update data
     * @return ApiResponse
     */
    public function updateDetails(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'update');
        return $this->client->put($endpoint, $data);
    }

    /**
     * Upload media for a product
     *
     * @param string $productId The product ID
     * @param string $mediaFile Path to the media file
     * @return ApiResponse
     */
    public function uploadMedia(string $productId, string $mediaFile): ApiResponse
    {
        $multipart = [
            [
                'name' => 'productId',
                'contents' => $productId,
            ],
            [
                'name' => 'media',
                'contents' => fopen($mediaFile, 'r'),
                'filename' => basename($mediaFile),
            ],
        ];

        $endpoint = $this->client->getEndpoint('product', 'upload_media');
        return $this->client->multipart($endpoint, $multipart);
    }

    /**
     * Get product media
     *
     * @param string $productId The product ID
     * @return ApiResponse
     */
    public function getMedia(string $productId): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'get_media');
        return $this->client->get($endpoint, [
            'productId' => $productId,
        ]);
    }

    /**
     * Delete product media
     *
     * @param array $data Data including media ID
     * @return ApiResponse
     */
    public function deleteMedia(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'delete_media');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Get product inventory details
     *
     * @param string $productId The product ID
     * @return ApiResponse
     */
    public function getInventoryDetails(string $productId): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'inventory_details');
        return $this->client->get($endpoint, [
            'productId' => $productId,
        ]);
    }

    /**
     * Upload file for digital download
     *
     * @param string $productId The product ID
     * @param string $file Path to the file
     * @return ApiResponse
     */
    public function addDigitalDownloadFile(string $productId, string $file): ApiResponse
    {
        $multipart = [
            [
                'name' => 'productId',
                'contents' => $productId,
            ],
            [
                'name' => 'media',
                'contents' => fopen($file, 'r'),
                'filename' => basename($file),
            ],
        ];

        $endpoint = $this->client->getEndpoint('product', 'add_digital_file');
        return $this->client->multipart($endpoint, $multipart);
    }

    /**
     * Get digital download files
     *
     * @param string $productId The product ID
     * @return ApiResponse
     */
    public function getDigitalDownloadFiles(string $productId): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'get_digital_files');
        return $this->client->get($endpoint, [
            'productId' => $productId,
        ]);
    }

    /**
     * Rename digital download files
     *
     * @param array $data Data including file ID and new name
     * @return ApiResponse
     */
    public function renameDigitalDownloadFile(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'rename_digital_file');
        return $this->client->put($endpoint, $data);
    }

    /**
     * Delete digital download files
     *
     * @param array $data Data including file ID
     * @return ApiResponse
     */
    public function deleteDigitalDownloadFile(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'delete_digital_file');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Set download link expiry
     *
     * @param array $data Data including productId and expiry duration
     * @return ApiResponse
     */
    public function setDownloadLinkExpiry(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'set_link_expiry');
        return $this->client->put($endpoint, $data);
    }

    /**
     * Add delivery fees
     *
     * @param array $data Data including productId, location, and fees
     * @return ApiResponse
     */
    public function addDeliveryFees(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'add_delivery_fees');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Get delivery fees
     *
     * @param string $productId The product ID
     * @return ApiResponse
     */
    public function getDeliveryFees(string $productId): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'get_delivery_fees');
        return $this->client->get($endpoint, [
            'productId' => $productId,
        ]);
    }

    /**
     * Update delivery fees
     *
     * @param array $data Update data
     * @return ApiResponse
     */
    public function updateDeliveryFees(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'update_delivery_fees');
        return $this->client->put($endpoint, $data);
    }

    /**
     * Delete delivery fees
     *
     * @param array $data Data including delivery fee ID
     * @return ApiResponse
     */
    public function deleteDeliveryFees(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'delete_delivery_fees');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Check if product slug exists
     *
     * @param array $data Data including slug
     * @return ApiResponse
     */
    public function slugExist(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'slug_exist');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Create a discount code
     *
     * @param array $data Discount code data
     * @return ApiResponse
     */
    public function addDiscountCode(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'add_discount');
        return $this->client->post($endpoint, $data);
    }

    /**
     * Get discount codes
     *
     * @param array $filters Filters including productId and code
     * @return ApiResponse
     */
    public function getDiscountCodes(array $filters = []): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'get_discounts');
        return $this->client->get($endpoint, $filters);
    }

    /**
     * Update discount code details
     *
     * @param array $data Update data
     * @return ApiResponse
     */
    public function updateDiscountCodeDetails(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'update_discount');
        return $this->client->put($endpoint, $data);
    }

    /**
     * Verify discount code
     *
     * @param array $data Data including productId and code
     * @return ApiResponse
     */
    public function verifyDiscountCode(array $data): ApiResponse
    {
        $endpoint = $this->client->getEndpoint('product', 'verify_discount');
        return $this->client->post($endpoint, $data);
    }
}
