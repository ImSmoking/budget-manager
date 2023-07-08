<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{

    public function __construct(
        private readonly SerializerInterface $serializer
    )
    {
    }

    public function getJsonResponse(array|object $content, array $context = [], int $status = Response::HTTP_OK): JsonResponse
    {
        if (is_object($content)) {
            $jsonContent = $this->serializer->serialize($content, 'json', $context);
            $arrayContent = json_decode($jsonContent, true);
        } else {
            $arrayContent = $content;
        }

        return new JsonResponse(['data' => $arrayContent], $status);
    }
}