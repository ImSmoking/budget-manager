<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CurrencyFixtures extends Fixture
{
    private array $fixtures = [
        [
            'name' => 'Euro',
            'code' => 'EUR'
        ],
        [
            'name' => 'United States dollar',
            'code' => 'USD'
        ],
        [
            'name' => 'Great Britain Pound',
            'code' => 'GBP'
        ],
        [
            'name' => 'Swiss franc',
            'code' => 'CHF'
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->fixtures as $fixture) {
            $currency = (new Currency())
                ->setName($fixture['name'])
                ->setCode($fixture['code']);

            $manager->persist($currency);
            $this->addReference('currency_' . $fixture['code'], $currency);
        }
        $manager->flush();
    }
}
