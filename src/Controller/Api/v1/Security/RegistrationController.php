<?php

namespace App\Controller\Api\v1\Security;

use App\Controller\Api\ApiController;
use App\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class RegistrationController extends ApiController
{


    #[Route('/api/register', name: 'api_register', methods: 'POST')]
    #[OA\Post(summary: "User registration", tags: ['Security'])]
    #[OA\RequestBody(
        description: "JSON Body",
        required: true,
        content: new Model(type: User::class, groups: ['register'])
    )]
    public function registerAction(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $request = $this->transformJsonContent($request);
        $username = $request->get("username");
        return $this->getJsonResponse(['message' => "Welcome {$username}! You have successfully registered!"]);
    }
}