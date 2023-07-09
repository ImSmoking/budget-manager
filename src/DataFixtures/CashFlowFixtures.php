<?php

namespace App\DataFixtures;

use App\Constant\CashFlowTypes;
use App\Entity\CashFlow;
use App\Handler\WalletBalanceHandler;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;

class CashFlowFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly WalletBalanceHandler $walletBalanceHandler
    )
    {
    }

    private array $fixtures = [
        [
            'type' => CashFlowTypes::EXPENSE,
            'wallet' => 'batman_wallet_1',
            'description' => 'New Car',
            'amount' => '1500',
            'category' => 'category_car',
            'dated_at' => '2023-06-01'
        ],
        [
            'type' => CashFlowTypes::EXPENSE,
            'wallet' => 'batman_wallet_1',
            'description' => 'New Watch',
            'amount' => '500',
            'category' => 'category_luxury',
            'dated_at' => '2023-07-01'
        ],
        [
            'type' => CashFlowTypes::EXPENSE,
            'wallet' => 'batman_wallet_1',
            'description' => 'New Yacht',
            'amount' => '500',
            'category' => 'category_luxury',
            'dated_at' => '2023-05-01'
        ],
        [
            'type' => CashFlowTypes::INCOME,
            'wallet' => 'batman_wallet_1',
            'description' => 'Monthly Income',
            'amount' => '55000',
            'category' => 'category_luxury',
            'dated_at' => '2023-07-01'
        ]
    ];

    public function load(ObjectManager $manager): void
    {

        foreach ($this->fixtures as $fixture) {
            $cashFlow = (new CashFlow())
                ->setType($fixture['type'])
                ->setWallet($this->getReference($fixture['wallet']))
                ->setDescription($fixture['description'])
                ->setAmount($fixture['amount'])
                ->setCategory($this->getReference($fixture['category']))
                ->setDatedAt(new DateTimeImmutable($fixture['dated_at']));

            $this->walletBalanceHandler->onCashFlowInsert($cashFlow->getWallet(), $cashFlow->getType(), $cashFlow->getAmount());
            $manager->persist($cashFlow);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WalletFixtures::class,
            CategoryFixtures::class
        ];
    }
}
