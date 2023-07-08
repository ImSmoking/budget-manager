<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Category;
use App\Factory\EntityFactory;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CategoryCrudService
{
    public function __construct(
        private readonly EntityFactory      $entityFactory,
        private readonly CategoryRepository $categoryRepository
    )
    {
    }

    public function createFromRequest(Request $request): Category
    {
        $jsonContent = $request->getContent();
        $groups = ['category:create'];
        /** @var Category $category */
        $category = $this->entityFactory->createFromJson($jsonContent, Category::class, $groups);
        $this->categoryRepository->save($category, true);

        return $category;
    }

    public function updateFromRequest(Category $category, Request $request): Category
    {
        $jsonContent = $request->getContent();
        $groups = ['category:create'];
        $context = [AbstractNormalizer::OBJECT_TO_POPULATE => $category];

        /** @var Category $categoryUpdated */
        $categoryUpdated = $this->entityFactory->createFromJson($jsonContent, Category::class, $groups, $context);
        $this->categoryRepository->save($categoryUpdated, true);

        return $categoryUpdated;
    }

    public function delete(Category $category): void
    {
        $this->categoryRepository->remove($category, true);
    }
}