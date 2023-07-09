<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UserRepository;

class UserCrudeService
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function getById(int $id)
    {
        
    }
}
