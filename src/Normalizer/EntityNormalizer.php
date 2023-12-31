<?php

declare(strict_types=1);

namespace App\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EntityNormalizer extends ObjectNormalizer
{

    protected EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface              $entityManager,
        ClassMetadataFactoryInterface       $classMetadataFactory = null,
        NameConverterInterface              $nameConverter = null,
        PropertyAccessorInterface           $propertyAccessor = null,
        PropertyTypeExtractorInterface      $propertyTypeExtractor = null,
        ClassDiscriminatorResolverInterface $classDiscriminatorResolver = null,
        callable                            $objectClassResolver = null,
        array                               $defaultContext = []
    )
    {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor, $classDiscriminatorResolver, $objectClassResolver, $defaultContext);
        $this->entityManager = $entityManager;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return strpos($type, 'App\\Entity\\') === 0 && (is_numeric($data) || is_string($data));
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        return $this->entityManager->find($type, $data);
    }
}