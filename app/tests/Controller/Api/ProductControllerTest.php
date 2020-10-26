<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testCreateProductInvalidData()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"title":""}'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCreateProductEmptyData()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ''
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testSuccess()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"title":"El imperio final"}'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}