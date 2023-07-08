<?php

namespace App\Security;

use App\Constant\UserRoles;
use App\Entity\User;
use App\Factory\EntityFactory;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationService
{
    public function __construct(
        private readonly EntityFactory               $entityFactory,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserRepository              $userRepository
    )
    {
    }

    public function registerFromRequest(Request $request): User
    {
        $jsonContent = $request->getContent();

        /** @var User $user */
        $user = $this->entityFactory->createFromJson($jsonContent, User::class, ['register']);

        $rawPassword = $user->getRawPassword();
        $user->setRoles([UserRoles::USER]);
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $rawPassword);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user, true);

        return $user;
    }
}