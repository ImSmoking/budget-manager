<?php

declare(strict_types=1);

namespace App\Tests\Category;

use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateTest extends ApiTestCase
{
    private const ENDPOINT_URI = '/api/category/create';

    public function testSuccess(): void
    {
        $this->login();

        $requestContent = [
            'name' => 'Cars',
            'color_hex' => '#FFFFFF'
        ];

        $this->client->request('POST', self::ENDPOINT_URI, [], [], [], json_encode($requestContent));

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('id', $content['data']);
        $this->assertArrayHasKey('name', $content['data']);
        $this->assertArrayHasKey('color_hex', $content['data']);
    }

    public function testFailOnUnauthorizedAccess(): void
    {
        $requestContent = [
            'name' => 'Cars',
            'color_hex' => '#FFFFFF'
        ];

        $this->client->request('POST', self::ENDPOINT_URI, [], [], [], json_encode($requestContent));

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testFailOnInvalidColorHex(): void
    {
        $this->login();

        $requestContent = [
            'name' => 'Cars',
            'color_hex' => 'Color'
        ];

        $this->client->request('POST', self::ENDPOINT_URI, [], [], [], json_encode($requestContent));

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
}