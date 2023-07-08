<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\EntityInterface;
use App\Service\ValidationService;
use Symfony\Component\Serializer\SerializerInterface;

final class EntityFactory
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidationService   $validator
    )
    {
    }

    public function createFromJson(string $data, string $class, array $groups = [], array $context = []): EntityInterface
    {
        return $this->create($data, $class, 'json', $groups, $context);
    }

    private function create(string|array $data, string $class, string $format, array $groups = [], array $context = []): EntityInterface
    {
        if (!empty($groups)) {
            $context['groups'] = $groups;
        }

        $entity = $this->serializer->deserialize($data, $class, $format, $context);
        $this->validator->validate($entity, $groups);

        return $entity;
    }
}