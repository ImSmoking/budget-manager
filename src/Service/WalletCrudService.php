<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Wallet;
use App\Factory\EntityFactory;
use App\Repository\WalletRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class WalletCrudService
{
    public function __construct(
        private readonly WalletRepository      $walletRepository,
        private readonly EntityFactory         $entityFactory,
        private readonly TokenStorageInterface $tokenStorage
    )
    {
    }

    public function createFromRequest(Request $request): Wallet
    {
        $contentJson = $request->getContent();
        /** @var Wallet $wallet */
        $wallet = $this->entityFactory->createFromJson($contentJson, Wallet::class, ['wallet:create']);
        $wallet->setUser($this->tokenStorage->getToken()->getUser());
        $this->walletRepository->save($wallet, true);

        return $wallet;
    }

    public function updateFromRequest(Wallet $wallet, Request $request): Wallet
    {
        $jsonContent = $request->getContent();
        $groups = ['wallet:update'];
        $context = [AbstractNormalizer::OBJECT_TO_POPULATE => $wallet];

        /** @var Wallet $walletUpdated */
        $walletUpdated = $this->entityFactory->createFromJson($jsonContent, Wallet::class, $groups, $context);
        $this->walletRepository->save($walletUpdated, true);

        return $walletUpdated;
    }

    public function delete(Wallet $wallet): void
    {
        $this->walletRepository->remove($wallet, true);
    }
}