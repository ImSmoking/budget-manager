<?php

declare(strict_types=1);

namespace App\Controller\Api\Security;

use App\Controller\Api\ApiController;
use App\Entity\User;
use App\Security\UserRegistrationService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'api_')]
class RegistrationController extends ApiController
{
    #[Route('/register', name: 'register', methods: 'POST')]
    #[OA\Post(summary: "User registration", tags: ['Security'])]
    #[OA\RequestBody(
        description: "JSON Body",
        required: true,
        content: new Model(type: User::class, groups: ['user:register'])
    )]
    public function registerAction(
        Request                 $request,
        UserRegistrationService $registrationService
    ): JsonResponse
    {
        $user = $registrationService->registerFromRequest($request);
        return $this->getJsonResponse(['message' => "Welcome {$user->getUsername()}, you have successfully registered!"]);
    }
}