<?php

declare(strict_types=1);

namespace App\Tests\Category;

use App\Repository\CategoryRepository;
use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateTest extends ApiTestCase
{
    private const ENDPOINT_URI = '/api/category/update/{id}';
    public function testSuccess(): void
    {
        $this->login();

        $requestContent = [
            'color_hex' => '#000000'
        ];

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryFood = $categoryRepository->findOneBy(['name' => 'Food']);
        $categoryId = (string)$categoryFood->getId();

        $endpoint = str_replace('{id}', $categoryId, self::ENDPOINT_URI);
        $this->client->request('PUT', $endpoint, [], [], [], json_encode($requestContent));

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('id', $content['data']);
        $this->assertArrayHasKey('name', $content['data']);
        $this->assertArrayHasKey('color_hex', $content['data']);
    }

    public function testFailOnNotFound(): void
    {
        $this->login();

        $requestContent = [
            'color_hex' => '#000111'
        ];

        $categoryId = (string)0;

        $endpoint = str_replace('{id}', $categoryId, self::ENDPOINT_URI);
        $this->client->request('PUT', $endpoint, [], [], [], json_encode($requestContent));

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testFailOnUnauthorizedAccess(): void
    {
        $requestContent = [
            'color_hex' => '#00000'
        ];

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryFood = $categoryRepository->findOneBy(['name' => 'Food']);
        $categoryId = (string)$categoryFood->getId();

        $endpoint = str_replace('{id}', $categoryId, self::ENDPOINT_URI);
        $this->client->request('PUT', $endpoint, [], [], [], json_encode($requestContent));

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}