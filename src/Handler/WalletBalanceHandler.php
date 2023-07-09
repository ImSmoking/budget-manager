<?php

declare(strict_types=1);

namespace App\Handler;


use App\Constant\CashFlowTypes;
use App\Entity\CashFlow;
use App\Entity\Wallet;
use App\Repository\WalletRepository;


class WalletBalanceHandler
{
    private WalletRepository $walletRepository;

    public function __construct(
        WalletRepository $walletRepository
    )
    {
        $this->walletRepository = $walletRepository;
    }

    public function onCashFlowUpdate(CashFlow $cashFlow, string $cashFlowTypeOld, string $cashFlowAmountOld): Wallet
    {
        $wallet = $this->onCashFlowDelete($cashFlow->getWallet(), $cashFlowTypeOld, $cashFlowAmountOld);
        return $this->onCashFlowInsert($wallet, $cashFlow->getType(), $cashFlow->getAmount());
    }

    public function onCashFlowInsert(Wallet $wallet, string $cashFlowType, string $cashFlowAmount): Wallet
    {
        if ($cashFlowType === CashFlowTypes::EXPENSE) {
            $newWalletBalance = bcsub($wallet->getBalance(), $cashFlowAmount, 2);
        } else {
            $newWalletBalance = bcadd($wallet->getBalance(), $cashFlowAmount, 2);
        }

        $wallet->setBalance($newWalletBalance);
        $this->walletRepository->save($wallet);

        return $wallet;
    }

    public function onCashFlowDelete(Wallet $wallet, string $cashFlowType, string $cashFlowAmount): Wallet
    {
        if ($cashFlowType === CashFlowTypes::EXPENSE) {
            $newWalletBalance = bcadd($wallet->getBalance(), $cashFlowAmount, 2);
        } else {
            $newWalletBalance = bcsub($wallet->getBalance(), $cashFlowAmount, 2);
        }

        $wallet->setBalance($newWalletBalance);
        $this->walletRepository->save($wallet);

        return $wallet;
    }
}