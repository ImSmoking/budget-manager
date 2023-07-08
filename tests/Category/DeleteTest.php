<?php

declare(strict_types=1);

namespace App\Tests\Category;

use App\Repository\CategoryRepository;
use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteTest extends ApiTestCase
{
    private const ENDPOINT_URI = '/api/category/delete/{id}';

    public function testSuccess(): void
    {
        $this->login();

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryFood = $categoryRepository->findOneBy(['name' => 'Cinema']);
        $categoryId = (string)$categoryFood->getId();

        $endpoint = str_replace('{id}', $categoryId, self::ENDPOINT_URI);
        $this->client->request('DELETE', $endpoint);

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testFailOnUnauthorizedAccess(): void
    {
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryFood = $categoryRepository->findOneBy(['name' => 'Streaming Services']);
        $categoryId = (string)$categoryFood->getId();

        $endpoint = str_replace('{id}', $categoryId, self::ENDPOINT_URI);
        $this->client->request('DELETE', $endpoint);

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testFailOnNotFound(): void
    {
        $this->login();

        $endpoint = str_replace('{id}', '0', self::ENDPOINT_URI);
        $this->client->request('DELETE', $endpoint);

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}