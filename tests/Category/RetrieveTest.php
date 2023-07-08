<?php

declare(strict_types=1);

namespace App\Tests\Category;

use App\Repository\CategoryRepository;
use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class RetrieveTest extends ApiTestCase
{
    private const ENDPOINT_URI = '/api/category/get/{id}';

    public function testSuccess(): void
    {
        $this->login();

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryFood = $categoryRepository->findOneBy(['name' => 'Food']);
        $categoryId = (string)$categoryFood->getId();

        $endpoint = str_replace('{id}', $categoryId, self::ENDPOINT_URI);
        $this->client->request('GET', $endpoint);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('name', $content['data']);
        $this->assertArrayHasKey('color_hex', $content['data']);
    }

    public function testFailOnUnauthorizedAccess(): void
    {
        $endpoint = str_replace('{id}', '1', self::ENDPOINT_URI);
        $this->client->request('GET', $endpoint);

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testFailOnNotFound(): void
    {
        $this->login();

        $endpoint = str_replace('{id}', '0', self::ENDPOINT_URI);
        $this->client->request('GET', $endpoint);

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}