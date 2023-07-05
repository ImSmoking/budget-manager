<?php

namespace App\Controller\Api\v1\Security;

use App\Controller\Api\ApiController;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use OpenApi\Attributes as OA;

class AuthenticationController extends ApiController
{
    #[Route('/api/login_check', name: 'api_login_check', methods: 'POST')]
    #[OA\Post(summary: "User login", tags: ['Security'])]
    #[OA\RequestBody(
        description: "JSON Body",
        required: true,
        content: new Model(type: User::class, groups: ['login'])
    )]
    public function getTokenAction(UserInterface $user, JWTTokenManagerInterface $JWTTokenManager): JsonResponse
    {
        return $this->getJsonResponse(['token' => $JWTTokenManager->create($user)]);
    }
}