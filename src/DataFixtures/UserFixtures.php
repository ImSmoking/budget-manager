<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\UserRoles;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private array $fixtures = [
        [
            'username' => 'Admin',
            'email' => 'admin@emil.com',
            'password' => 'Password12345!',
            'roles' => UserRoles::ADMIN_ROLES
        ],
        [
            'username' => 'Batman',
            'email' => 'batman@dc.com',
            'password' => 'Password12345!',
            'roles' => UserRoles::DEFAULT_ROLES
        ],
        [
            'username' => 'Superman',
            'email' => 'superman@dc.com',
            'password' => 'Password12345!',
            'roles' => UserRoles::DEFAULT_ROLES
        ],
        [
            'username' => 'Spider.Man',
            'email' => 'spider.man@mc.com',
            'password' => 'Password12345!',
            'roles' => UserRoles::DEFAULT_ROLES
        ],
        [
            'username' => 'Iron.Man',
            'email' => 'iron.man@mc.com',
            'password' => 'Password12345!',
            'roles' => UserRoles::DEFAULT_ROLES
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
                ->setEmail($fixture['email'])
                ->setRoles($fixture['roles']);

            $hashedPassword = $this->userPasswordHasher->hashPassword($user, $fixture['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $this->addReference('user_' . $fixture['email'], $user);
        }

        $manager->flush();
    }
}