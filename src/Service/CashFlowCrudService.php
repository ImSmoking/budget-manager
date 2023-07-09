<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CashFlow;
use App\Factory\EntityFactory;
use App\Handler\WalletBalanceHandler;
use App\Repository\CashFlowRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CashFlowCrudService
{
    public function __construct(
        private readonly EntityFactory          $entityFactory,
        private readonly CashFlowRepository     $cashFlowRepository,
        private readonly WalletBalanceHandler   $walletBalanceHandler
    )
    {
    }

    public function createFromRequest(Request $request): CashFlow
    {
        $jsonContent = $request->getContent();
        $groups = ['cash_flow:create'];
        /** @var CashFlow $cashFlow */
        $cashFlow = $this->entityFactory->createFromJson($jsonContent, CashFlow::class, $groups);
        $this->walletBalanceHandler->onCashFlowInsert($cashFlow->getWallet(), $cashFlow->getType(), $cashFlow->getAmount());
        $this->cashFlowRepository->save($cashFlow, true);

        return $cashFlow;
    }

    public function delete(CashFlow $cashFlow): void
    {
        $this->walletBalanceHandler->onCashFlowDelete($cashFlow->getWallet(), $cashFlow->getType(), $cashFlow->getAmount());
        $this->cashFlowRepository->remove($cashFlow, true);
    }

    public function updateFromRequest(CashFlow $cashFlow, Request $request): CashFlow
    {
        $jsonContent = $request->getContent();
        $groups = ['cash_flow:create'];
        $context = [AbstractNormalizer::OBJECT_TO_POPULATE => $cashFlow];

        $typeOld = $cashFlow->getType();
        $amountOld = $cashFlow->getAmount();
        /** @var CashFlow $cashFlowUpdated */
        $cashFlowUpdated = $this->entityFactory->createFromJson($jsonContent, CashFlow::class, $groups, $context);
        $this->walletBalanceHandler->onCashFlowUpdate($cashFlowUpdated, $typeOld, $amountOld);
        $this->cashFlowRepository->save($cashFlowUpdated, true);

        return $cashFlowUpdated;
    }
}