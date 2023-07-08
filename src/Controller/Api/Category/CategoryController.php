<?php

declare(strict_types=1);

namespace App\Controller\Api\Category;

use App\Controller\Api\ApiController;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoryCrudService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/category', name: 'api_category_')]
class CategoryController extends ApiController
{

    private CategoryCrudService $categoryCrudService;

    public function __construct(
        SerializerInterface $serializer,
        CategoryCrudService $categoryCrudService
    )
    {
        parent::__construct($serializer);
        $this->categoryCrudService = $categoryCrudService;
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    #[
        OA\Post(summary: "Create Category", tags: ['Category']),
        OA\RequestBody(
            description: "Single Category JSON item.",
            required: true,
            content: new Model(type: Category::class, groups: ['category:create'])
        ),
        OA\Response(
            response: Response::HTTP_CREATED,
            description: "Single Category JSON item.",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        ref: new Model(type: Category::class, groups: ['category:get']),
                        type: 'object'
                    )
                ]
            )
        )
    ]
    public function createAction(
        Request             $request
    ): JsonResponse
    {
        $category = $this->categoryCrudService->createFromRequest($request);
        return $this->getJsonResponse($category, ['groups' => ['category:get']], Response::HTTP_CREATED);
    }

    #[Route('/update/{id}', name: 'update', methods: ['PUT'])]
    #[
        OA\Put(summary: "Update Category", tags: ['Category']),
        OA\RequestBody(
            description: "Single Category JSON item",
            required: true,
            content: new Model(type: Category::class, groups: ['category:create'])
        ),
        OA\Response(
            response: Response::HTTP_OK,
            description: "Single Category JSON item",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        ref: new Model(type: Category::class, groups: ['category:get']),
                        type: 'object'
                    )
                ]
            )
        )
    ]
    public function updateAction(
        Category            $category,
        Request             $request
    ): JsonResponse
    {
        $category = $this->categoryCrudService->updateFromRequest($category, $request);
        return $this->getJsonResponse($category, ['groups' => ['category:get']]);
    }

    #[Route('/get/{id}', name: 'get', methods: ['GET'])]
    #[
        OA\Get(summary: "Category by ID", tags: ['Category']),
        OA\Response(
            response: Response::HTTP_OK,
            description: "Single Category JSON item",
            content: new Model(type: Category::class, groups: ['category:get'])
        )
    ]
    public function getAction(Category $category): JsonResponse
    {
        return $this->getJsonResponse($category, ['groups' => ['category:get']]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    #[
        OA\Delete(summary: "Category by ID", tags: ['Category']),
        OA\Response(
            response: Response::HTTP_NO_CONTENT,
            description: "204 No Content"
        )
    ]
    public function deleteAction(Category $category): JsonResponse
    {
        $this->categoryCrudService->delete($category);
        return $this->getJsonResponse([], [], Response::HTTP_NO_CONTENT);
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    #[
        OA\Get(summary: "All Categories", tags: ['Category']),
        OA\Response(
            response: Response::HTTP_OK,
            description: "List of Category JSON items",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(new Model(type: Category::class, groups: ['category:get']))
                    )
                ]
            )
        )
    ]
    public function listAction(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();
        return $this->getJsonResponse($categories, ['groups' => ['category:get']]);
    }
}