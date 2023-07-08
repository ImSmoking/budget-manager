<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\Api\ApiController;
use App\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'api_user_')]
class UserController extends ApiController
{

    #[Route('/info', name: 'info', methods: ['GET'])]
    #[OA\Get(summary: "User info", tags: ['User'])]
    #[OA\Response(
        response: 200,
        description: "JSON Body",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: new Model(type: User::class, groups: ['user:get', 'timestamp']),
                    type: 'object'
                )
            ]
        )
    )]
    public function infoAction(): JsonResponse
    {
        $this->getUser();
        return $this->getJsonResponse($this->getUser(), ['groups' => ['user:get', 'timestamp']]);
    }
}