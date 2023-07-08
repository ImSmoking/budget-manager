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
        ],
        [
            'name' => 'Bank Account',
        ],
        [
            'name' => 'Credit Card',
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->fixtures as $fixture) {
            $walletType = (new WalletType())
                ->setName($fixture['name']);

            $manager->persist($walletType);
        }
        $manager->flush();
    }
}
