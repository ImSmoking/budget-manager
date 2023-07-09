<?php

declare(strict_types=1);

namespace App\Controller\Api\CashFlow;

use App\Constant\CashFlowTypes;
use App\Controller\Api\ApiController;
use App\Entity\CashFlow;
use App\Repository\CashFlowRepository;
use App\Security\Voter\CashFlowVoter;
use App\Service\CashFlowCrudService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class CashFlowController extends ApiController
{
    public function __construct(
        private readonly CashFlowCrudService $cashFlowCrudService,
        SerializerInterface                  $serializer
    )
    {
        parent::__construct($serializer);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    #[
        OA\Post(summary: "Create Cash Flow", tags: ['Cash Flow']),
        OA\RequestBody(
            description: "Single Cash Flow JSON item.",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'type', type: 'string', example: 'expense'),
                    new OA\Property(property: 'wallet', type: 'int', example: 1),
                    new OA\Property(property: 'description', type: 'string', example: 'Burger'),
                    new OA\Property(property: 'amount', type: 'string', example: '5.99'),
                    new OA\Property(property: 'category', type: 'int', example: 1),
                    new OA\Property(property: 'dated_at', type: 'datetime', example: '2023-07-01 12:45')
                ]
            )
        ),
        OA\Response(
            response: Response::HTTP_CREATED,
            description: "Single Cash Flow JSON item.",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        ref: new Model(type: CashFlow::class, groups: ['cash_flow:get', 'timestamp', 'category:get', 'wallet:get']),
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
        $cashFlow = $this->cashFlowCrudService->createFromRequest($request);
        return $this->getJsonResponse($cashFlow, ['groups' => ['cash_flow:get', 'timestamp', 'category:get', 'wallet:get']], Response::HTTP_CREATED);
    }

    #[Route('/update/{id}', name: 'update', methods: ['PUT'])]
    #[
        OA\Put(summary: "Update Cash Flow", tags: ['Cash Flow']),
        OA\RequestBody(
            description: "Single Cash Flow JSON item",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'type', type: 'string', example: 'expense'),
                    new OA\Property(property: 'wallet', type: 'int', example: 1),
                    new OA\Property(property: 'description', type: 'string', example: 'Burger'),
                    new OA\Property(property: 'amount', type: 'string', example: '5.99'),
                    new OA\Property(property: 'category', type: 'int', example: 1),
                    new OA\Property(property: 'dated_at', type: 'datetime', example: '2023-07-01 12:45')
                ]
            )
        ),
        OA\Response(
            response: Response::HTTP_OK,
            description: "Single Cash Flow JSON item",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        ref: new Model(type: CashFlow::class, groups: ['cash_flow:get', 'category:get']),
                        type: 'object'
                    )
                ]
            )
        )
    ]
    #[IsGranted(CashFlowVoter::EDIT,'cashFlow')]
    public function updateAction(
        CashFlow $cashFlow,
        Request  $request
    ): JsonResponse
    {
        $cashFlow = $this->cashFlowCrudService->updateFromRequest($cashFlow, $request);
        return $this->getJsonResponse($cashFlow, ['groups' => ['cash_flow:get']]);
    }

    #[Route('/get/{id}', name: 'get', methods: ['GET'])]
    #[
        OA\Get(summary: "Cash Flow by ID", tags: ['Cash Flow']),
        OA\Response(
            response: Response::HTTP_OK,
            description: "Single Cash Flow JSON item",
            content: new Model(type: CashFlow::class, groups: ['cash_flow:get', 'timestamp', 'category:get', 'wallet:get'])
        )
    ]
    #[IsGranted(CashFlowVoter::VIEW,'cashFlow')]
    public function getAction(CashFlow $cashFlow): JsonResponse
    {
        return $this->getJsonResponse($cashFlow, ['groups' => ['cash_flow:get', 'timestamp', 'category:get', 'wallet:get']]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    #[
        OA\Delete(summary: "Cash Flow by ID", tags: ['Cash Flow']),
        OA\Response(
            response: Response::HTTP_NO_CONTENT,
            description: "204 No Content"
        )
    ]
    #[IsGranted(CashFlowVoter::DELETE,'cashFlow')]
    public function deleteAction(CashFlow $cashFlow): JsonResponse
    {
        $this->cashFlowCrudService->delete($cashFlow);
        return $this->getJsonResponse([], [], Response::HTTP_NO_CONTENT);
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    #[
        OA\Get(summary: "All Cash Flows", tags: ['Cash Flow']),
        OA\Response(
            response: Response::HTTP_OK,
            description: "List of Cash Flows JSON items",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(new Model(type: CashFlow::class, groups: ['cash_flow:get', 'timestamp', 'category:get', 'wallet:get']))
                    )
                ]
            )
        )
    ]
    public function listAction(CashFlowRepository $cashFlowRepository): JsonResponse
    {
        $cashFlows = $cashFlowRepository->findAll();
        return $this->getJsonResponse($cashFlows, ['groups' => ['cash_flow:get', 'timestamp', 'category:get', 'wallet:get']]);
    }

    #[Route('/list/types', name: 'list_types', methods: ['GET'])]
    #[
        OA\Get(summary: "Valid Cash Flow types", tags: ['Cash Flow']),
    ]
    public function listCashFlowTypesAction(): JsonResponse
    {
        return $this->getJsonResponse(CashFlowTypes::TYPES_ARRAY);
    }
}