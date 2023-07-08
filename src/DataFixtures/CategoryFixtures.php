<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private array $fixtures = [
        [
            'name' => 'Food',
            'color_hex' => '#CCC000'
        ],
        [
            'name' => 'Drinks',
            'color_hex' => '#BBB111'
        ],
        [
            'name' => 'Cinema',
            'color_hex' => '#AAA111'
        ],
        [
            'name' => 'Streaming Services',
            'color_hex' => '#CCC111'
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->fixtures as $fixture) {
            $category = (new Category())
                ->setName($fixture['name'])
                ->setColorHex($fixture['color_hex']);

            $manager->persist($category);
        }
        $manager->flush();
    }
}
