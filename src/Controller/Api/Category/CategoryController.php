<?php

namespace App\Controller\Api\Category;

use App\Controller\Api\ApiController;
use App\Entity\Category;
use App\Factory\EntityFactory;
use App\Repository\CategoryRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[Route('/category', name: 'api_category_')]
class CategoryController extends ApiController
{

    #[Route('/create', name: 'create', methods: ['POST'])]
    #[OA\Post(summary: "Create Category", tags: ['Category'])]
    #[OA\RequestBody(
        description: "JSON Body",
        required: true,
        content: new Model(type: Category::class, groups: ['create'])
    )]
    public function createAction(
        Request            $request,
        EntityFactory      $entityFactory,
        CategoryRepository $categoryRepository
    ): JsonResponse
    {
        $content = $request->getContent();
        /** @var Category $category */
        $category = $entityFactory->createFromJson($content, Category::class, ['create']);
        $categoryRepository->save($category, true);

        return $this->getJsonResponse($category, ['groups' => ['create']]);

    }

    #[Route('/get/{id}', name: 'get', methods: ['GET'])]
    #[OA\GET(summary: "Get Category by ID", tags: ['Category'])]
    #[OA\Response(
        response: 200,
        description: "JSON Body",
        content: new Model(type: Category::class, groups: ['get'])
    )]
    public function getAction(Category $category): JsonResponse
    {
        return $this->getJsonResponse($category, ['groups' => ['get']]);
    }
}