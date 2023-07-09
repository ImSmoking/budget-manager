<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private array $fixtures = [
        [
            'username' => 'Batman',
            'email' => 'batman@dc.com',
            'password' => 'Password12345!'
        ],
        [
            'username' => 'Superman',
            'email' => 'superman@dc.com',
            'password' => 'Password12345!'
        ],
        [
            'username' => 'Spider.Man',
            'email' => 'spider.man@mc.com',
            'password' => 'Password12345!'
        ],
        [
            'username' => 'Iron.Man',
            'email' => 'iron.man@mc.com',
            'password' => 'Password12345!'
        ],
    ];

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    )
    {
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->fixtures as $fixture) {
            $user = (new User())->setUsername($fixture['username'])
                ->setEmail($fixture['email']);

            $hashedPassword = $this->userPasswordHasher->hashPassword($user, $fixture['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $this->addReference('user_' . $fixture['email'], $user);
        }

        $manager->flush();
    }
}