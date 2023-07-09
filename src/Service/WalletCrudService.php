<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Wallet;
use App\Factory\EntityFactory;
use App\Form\WalletType;
use App\Repository\CurrencyRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Repository\WalletTypeRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class WalletCrudService
{
    public function __construct(
        private readonly EntityFactory        $entityFactory,
        private readonly WalletRepository     $walletRepository,
        private readonly UserRepository       $userRepository,
        private readonly WalletTypeRepository $walletTypeRepository,
        private readonly CurrencyRepository $currencyRepository,
        private readonly FormFactoryInterface $formFactory
    )
    {
    }

    public function createFromRequest(Request $request): Wallet
    {
        $contentJson = $request->getContent();
        $contentArray = json_decode($contentJson, true);

        $form = $this->formFactory->create(WalletType::class, new Wallet());
        $form->submit($contentArray);
        /** @var Wallet $object */
        $wallet = $form->getData();
        
        /** @var Wallet $wallet */
#        $wallet = $this->entityFactory->createFromJson($contentJson, Wallet::class, ['wallet:create']);
#        $currency = $this->getCurrency($contentArray['currency']['id']);


        $this->walletRepository->save($wallet, true);

        return $wallet;
    }
}