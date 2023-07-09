<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Currency;
use App\Entity\WalletType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WalletTypeFixtures extends Fixture
{
    private array $fixtures = [
        [
            'name' => 'Cash',
            'fixture_reference' => 'wallet_type_cash'
        ],
        [
            'name' => 'Bank Account',
            'fixture_reference' => 'wallet_type_bank_account'
        ],
        [
            'name' => 'Credit Card',
            'fixture_reference' => 'wallet_type_credit_card'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->fixtures as $fixture) {
            $walletType = (new WalletType())
                ->setName($fixture['name']);

            $manager->persist($walletType);
            $this->addReference($fixture['fixture_reference'], $walletType);
        }
        $manager->flush();
    }
}
