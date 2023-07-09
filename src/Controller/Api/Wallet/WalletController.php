<?php

declare(strict_types=1);

namespace App\Controller\Api\Wallet;

use App\Constant\UserRoles;
use App\Controller\Api\ApiController;
use App\Entity\Wallet;
use App\Repository\WalletRepository;
use App\Security\Voter\WalletVoter;
use App\Service\WalletCrudService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/wallet', name: 'api_wallet_')]
class WalletController extends ApiController
{

    public function __construct(
        SerializerInterface                $serializer,
        private readonly WalletCrudService $walletCrudService
    )
    {
        parent::__construct($serializer);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    #[
        OA\Post(summary: "Create Wallet", tags: ['Wallet']),
        OA\RequestBody(
            description: "Single Wallet JSON item.",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'New Wallet'),
                    new OA\Property(property: 'balance', type: 'string', example: '1000.00'),
                    new OA\Property(property: 'currency', type: 'int', example: 1),
                    new OA\Property(property: 'type', type: 'int', example: 1)
                ]
            )
        ),
        OA\Response(
            response: Response::HTTP_CREATED,
            description: "Single Wallet JSON item.",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        ref: new Model(type: Wallet::class, groups: ['wallet:get']),
                        type: 'object'
                    )
                ]
            )
        )
    ]
    public function createAction(
        Request $request
    ): JsonResponse
    {
        $wallet = $this->walletCrudService->createFromRequest($request);
        return $this->getJsonResponse($wallet, ['groups' => ['wallet:get']], Response::HTTP_CREATED);
    }

    #[Route('/update/{id}', name: 'update', methods: ['PUT'])]
    #[
        OA\Put(summary: "Update Wallet", tags: ['Wallet']),
        OA\RequestBody(
            description: "Single Wallet JSON item",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'New Wallet'),
                    new OA\Property(property: 'balance', type: 'string', example: '1000.00'),
                    new OA\Property(property: 'currency', type: 'int', example: 1),
                    new OA\Property(property: 'type', type: 'int', example: 1)
                ]
            )
        ),
        OA\Response(
            response: Response::HTTP_OK,
            description: "Update Wallet JSON item",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        ref: new Model(type: Wallet::class, groups: ['wallet:get']),
                        type: 'object'
                    )
                ]
            )
        )
    ]
    #[IsGranted(WalletVoter::EDIT, 'wallet')]
    public function updateAction(
        Wallet  $wallet,
        Request $request
    ): JsonResponse
    {
        $wallet = $this->walletCrudService->updateFromRequest($wallet, $request);
        return $this->getJsonResponse($wallet, ['groups' => ['wallet:get']]);
    }

    #[Route('/get/{id}', name: 'get', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    #[
        OA\Get(summary: "Wallet by ID", tags: ['Wallet']),
        OA\Response(
            response: Response::HTTP_OK,
            description: "Single Wallet JSON item",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        ref: new Model(type: Wallet::class, groups: ['wallet:get', 'currency:get', 'wallet-type:get']),
                        type: 'object'
                    )
                ]
            )
        )
    ]
    #[IsGranted(WalletVoter::VIEW, 'wallet')]
    public function getAction(Wallet $wallet): JsonResponse
    {
        return $this->getJsonResponse($wallet, ['groups' => ['wallet:get', 'currency:get', 'wallet-type:get']]);
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    #[
        OA\Get(summary: "All Wallets", tags: ['Wallet']),
        OA\Response(
            response: Response::HTTP_OK,
            description: "List of Wallet Type JSON items",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(new Model(type: Wallet::class, groups: ['wallet:get']))
                    )
                ]
            )
        )
    ]
    public function listAction(WalletRepository $walletRepository): JsonResponse
    {
        if ($this->getUser()->hasRole(UserRoles::ADMIN)) {
            $wallets = $walletRepository->findAll();
        } else {
            $wallets = $walletRepository->findBy(['user' => $this->getUser()]);
        }

        return $this->getJsonResponse($wallets, ['groups' => ['wallet:get']]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    #[
        OA\Delete(summary: "Wallet by ID", tags: ['Wallet']),
        OA\Response(
            response: Response::HTTP_NO_CONTENT,
            description: "204 No Content"
        )
    ]
    #[IsGranted(WalletVoter::DELETE, 'wallet')]
    public function deleteAction(Wallet $wallet): JsonResponse
    {
        $this->walletCrudService->delete($wallet);
        return $this->getJsonResponse([], [], Response::HTTP_NO_CONTENT);
    }
}