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
            'color_hex' => '#CCC000',
            'ref' => 'category_food'
        ],
        [
            'name' => 'Drinks',
            'color_hex' => '#BBB111',
            'ref' => 'category_drinks'
        ],
        [
            'name' => 'Cinema',
            'color_hex' => '#AAA111',
            'ref' => 'category_cinema'
        ],
        [
            'name' => 'Streaming Services',
            'color_hex' => '#CCC111',
            'ref' => 'category_ss'
        ],
        [
            'name' => 'Car',
            'color_hex' => '#DDD111',
            'ref' => 'category_car'
        ],
        [
            'name' => 'Luxury',
            'color_hex' => '#EEE111',
            'ref' => 'category_luxury'
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->fixtures as $fixture) {
            $category = (new Category())
                ->setName($fixture['name'])
                ->setColorHex($fixture['color_hex']);

            $manager->persist($category);
            $this->addReference($fixture['ref'],$category);
        }
        $manager->flush();
    }
}
