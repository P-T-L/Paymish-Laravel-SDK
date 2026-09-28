<?php

namespace Bhekor\Paymish\Tests\Unit;

use Bhekor\Paymish\Responses\ApiResponse;
use PHPUnit\Framework\TestCase;

class ApiResponseTest extends TestCase
{
    protected ApiResponse $response;

    protected function setUp(): void
    {
        $this->response = new ApiResponse(200, [
            'message' => 'Success',
            'data' => [
                'id' => 123,
                'name' => 'Test',
            ],
        ]);
    }

    public function test_it_returns_status_code()
    {
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function test_it_checks_if_successful()
    {
        $this->assertTrue($this->response->successful());

        $errorResponse = new ApiResponse(400, []);
        $this->assertFalse($errorResponse->successful());
    }

    public function test_it_returns_all_data()
    {
        $data = $this->response->all();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('message', $data);
    }

    public function test_it_gets_specific_value()
    {
        $this->assertEquals('Success', $this->response->get('message'));
        $this->assertEquals(123, $this->response->get('data.id'));
        $this->assertNull($this->response->get('nonexistent'));
    }

    public function test_it_gets_message()
    {
        $this->assertEquals('Success', $this->response->message());
    }

    public function test_it_checks_if_key_exists()
    {
        $this->assertTrue($this->response->has('message'));
        $this->assertFalse($this->response->has('nonexistent'));
    }

    public function test_it_implements_array_access()
    {
        $this->assertEquals('Success', $this->response['message']);
        $this->assertTrue(isset($this->response['message']));
    }

    public function test_it_can_be_converted_to_array()
    {
        $array = $this->response->toArray();
        $this->assertIsArray($array);
        $this->assertEquals('Success', $array['message']);
    }

    public function test_it_can_be_converted_to_json()
    {
        $json = $this->response->toJson();
        $this->assertIsString($json);
        $this->assertStringContainsString('Success', $json);
    }
}
