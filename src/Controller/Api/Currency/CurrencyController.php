<?php

declare(strict_types=1);

namespace App\Controller\Api\Currency;

use App\Controller\Api\ApiController;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/currency', name: 'api_currency_')]
class CurrencyController extends ApiController
{
    #[Route('/get/{id}', name: 'get', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    #[
        OA\Get(summary: "Currency by ID", tags: ['Currency']),
        OA\Response(
            response: Response::HTTP_OK,
            description: "Single Currency JSON item",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        ref: new Model(type: Currency::class, groups: ['currency:get']),
                        type: 'object'
                    )
                ]
            )
        )
    ]
    public function getAction(Currency $currency): JsonResponse
    {
        return $this->getJsonResponse($currency, ['groups' => ['currency:get']]);
    }

    #[Route('/get/by-code/{code}', name: 'get_by_code', requirements: ['code' => '[A-Za-z]{3}'], methods: ['GET'])]
    #[
        OA\Get(summary: "Currency by Code", tags: ['Currency']),
        OA\Response(
            response: Response::HTTP_OK,
            description: "Single Currency JSON item",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        ref: new Model(type: Currency::class, groups: ['currency:get']),
                        type: 'object'
                    )
                ]
            )
        )
    ]
    public function getByCodeAction(Currency $currency): JsonResponse
    {
        return $this->getJsonResponse($currency, ['groups' => ['currency:get']]);
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    #[
        OA\Get(summary: "All Currencies", tags: ['Currency']),
        OA\Response(
            response: Response::HTTP_OK,
            description: "List of Currency JSON items",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(new Model(type: Currency::class, groups: ['currency:get']))
                    )
                ]
            )
        )
    ]
    public function listAction(CurrencyRepository $currencyRepository): JsonResponse
    {
        $currencies = $currencyRepository->findAll();
        return $this->getJsonResponse($currencies, ['groups' => ['currency:get']]);
    }
}