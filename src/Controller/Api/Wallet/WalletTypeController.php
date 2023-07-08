<?php

declare(strict_types=1);

namespace App\Controller\Api\Wallet;

use App\Controller\Api\ApiController;
use App\Entity\WalletType;
use App\Repository\WalletTypeRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/wallet-type', name: 'api_wallet_type_')]
class WalletTypeController extends ApiController
{
    #[Route('/get/{id}', name: 'get', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    #[
        OA\Get(summary: "Wallet Type by ID", tags: ['Wallet']),
        OA\Response(
            response: Response::HTTP_OK,
            description: "Single Wallet Type JSON item",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        ref: new Model(type: WalletType::class, groups: ['wallet-type:get']),
                        type: 'object'
                    )
                ]
            )
        )
    ]
    public function getAction(WalletType $walletType): JsonResponse
    {
        return $this->getJsonResponse($walletType, ['groups' => ['wallet-type:get']]);
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    #[
        OA\Get(summary: "All Wallet Types", tags: ['Wallet']),
        OA\Response(
            response: Response::HTTP_OK,
            description: "List of Wallet Type JSON items",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(new Model(type: WalletType::class, groups: ['wallet-type:get']))
                    )
                ]
            )
        )
    ]
    public function listAction(WalletTypeRepository $walletTypeRepository): JsonResponse
    {
        $walletTypes = $walletTypeRepository->findAll();
        return $this->getJsonResponse($walletTypes, ['groups' => ['wallet-type:get']]);
    }
}