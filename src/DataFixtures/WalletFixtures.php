<?php

namespace App\DataFixtures;

use App\Entity\Wallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WalletFixtures extends Fixture implements DependentFixtureInterface
{
    private array $fixtures = [
        [
            'user_ref' => 'user_batman@dc.com',
            'currency_ref' => 'currency_USD',
            'type_ref' => 'wallet_type_bank_account',
            'name' => 'USD Account',
            'balance' => '150450000'
        ],
        [
            'user_ref' => 'user_batman@dc.com',
            'currency_ref' => 'currency_GBP',
            'type_ref' => 'wallet_type_bank_account',
            'name' => 'GBP Account',
            'balance' => '28900000'
        ],
        [
            'user_ref' => 'user_spider.man@mc.com',
            'currency_ref' => 'currency_USD',
            'type_ref' => 'wallet_type_bank_account',
            'name' => 'Bank Account',
            'balance' => '108'
        ],
        [
            'user_ref' => 'user_spider.man@mc.com',
            'currency_ref' => 'currency_USD',
            'type_ref' => 'wallet_type_cash',
            'name' => 'Cash',
            'balance' => '15'
        ],
        [
            'user_ref' => 'user_superman@dc.com',
            'currency_ref' => 'currency_USD',
            'type_ref' => 'wallet_type_bank_account',
            'name' => 'Bank Account',
            'balance' => '15980.90'
        ],
        [
            'user_ref' => 'user_superman@dc.com',
            'currency_ref' => 'currency_USD',
            'type_ref' => 'wallet_type_cash',
            'name' => 'Cash',
            'balance' => '560.10'
        ],
        [
            'user_ref' => 'user_iron.man@mc.com',
            'currency_ref' => 'currency_USD',
            'type_ref' => 'wallet_type_bank_account',
            'name' => 'USD Account',
            'balance' => '19088900.10'
        ],
        [
            'user_ref' => 'user_iron.man@mc.com',
            'currency_ref' => 'currency_CHF',
            'type_ref' => 'wallet_type_bank_account',
            'name' => 'CHF Account',
            'balance' => '15009909.99'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->fixtures as $fixture) {

            $wallet = (new Wallet())
                ->setUser($this->getReference($fixture['user_ref']))
                ->setCurrency($this->getReference($fixture['currency_ref']))
                ->setType($this->getReference($fixture['type_ref']))
                ->setName($fixture['name'])
                ->setBalance($fixture['balance']);

            $manager->persist($wallet);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CurrencyFixtures::class,
            WalletTypeFixtures::class
        ];
    }
}
