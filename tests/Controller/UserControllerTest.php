<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testIndexWithNoneParam()
    {
        // Create symfony client
        $client = static::createClient();

        // Send a GET request
        $client->request('GET', '/api/users');

        // Assert status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Check that the response content is as expected
        $result = json_decode($client->getResponse()->getContent());
        $this->assertTrue($result->success);
        $this->assertEquals(500,$result->data->totalItems);
    }

    public function testIndexWithParam()
    {
        // Create symfony client
        $client = static::createClient();

        // Send a GET request
        $client->request('GET', '/api/users', [
            'is_active' => '1',
            'is_member' => '1',
            'user_type' => '3,2,1',
            'last_login_start_at' => '2019-10-20 20:07:33',
            'last_login_end_at' => '2020-10-20 20:07:34',
        ]);

        // Assert status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Check that the response content is as expected
        $result = json_decode($client->getResponse()->getContent());
        $this->assertTrue($result->success);
        $this->assertEquals(8,$result->data->totalItems);
    }

    public function testIndexWithErrorUserType()
    {
        // Create symfony client
        $client = static::createClient();

        // Send a GET request
        $client->request('GET', '/api/users', [
            'user_type' => '3,2,1a',
        ]);

        // Assert status code
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        // Check that the response content is as expected
        $expectedResponse = '{"success":false,"error":{"message":"Invalid parameters","details":{"[user_type][2]":"The value you selected is not a valid choice."}}}';
        $this->assertJsonStringEqualsJsonString($expectedResponse, $client->getResponse()->getContent());

    }

}
