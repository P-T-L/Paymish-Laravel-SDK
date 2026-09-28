<?php

namespace Bhekor\Paymish\Tests\Unit;

use Bhekor\Paymish\PaymishClient;
use Bhekor\Paymish\Api\AuthApi;
use Bhekor\Paymish\Api\TransactionApi;
use PHPUnit\Framework\TestCase;

class PaymishClientTest extends TestCase
{
    protected PaymishClient $client;

    protected function setUp(): void
    {
        $this->client = new PaymishClient(
            'test-api-key',
            'https://api.paymish.com',
            30,
            false
        );
    }

    public function test_it_can_be_instantiated()
    {
        $this->assertInstanceOf(PaymishClient::class, $this->client);
    }

    public function test_it_returns_auth_api_instance()
    {
        $this->assertInstanceOf(AuthApi::class, $this->client->auth());
    }

    public function test_it_returns_transaction_api_instance()
    {
        $this->assertInstanceOf(TransactionApi::class, $this->client->transaction());
    }

    public function test_it_can_set_bearer_token()
    {
        $this->client->setBearerToken('test-token');
        $this->assertEquals('test-token', $this->client->getBearerToken());
    }

    public function test_it_can_check_live_mode()
    {
        $this->assertFalse($this->client->isLive());

        $liveClient = new PaymishClient('test-key', 'https://api.paymish.com', 30, true);
        $this->assertTrue($liveClient->isLive());
    }
}
