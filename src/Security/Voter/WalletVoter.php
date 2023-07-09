<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Constant\UserRoles;
use App\Entity\User;
use App\Entity\Wallet;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class WalletVoter extends Voter
{
    const VIEW = 'view';
    const DELETE = 'delete';
    const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::DELETE, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof Wallet) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Wallet $wallet */
        $wallet = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($wallet, $user),
            self::EDIT => $this->canEdit($wallet, $user),
            self::DELETE => $this->canDelete($wallet, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(Wallet $wallet, User $user): bool
    {
        return $this->canAccess($wallet, $user);
    }

    private function canEdit(Wallet $wallet, User $user): bool
    {
        return $this->canAccess($wallet, $user);
    }

    private function canDelete(Wallet $wallet, User $user): bool
    {
        return $this->canAccess($wallet, $user);
    }

    private function canAccess(Wallet $wallet, User $user): bool
    {
        if ($wallet->getUser() === $user) {
            return true;
        }

        return $user->hasRole(UserRoles::ADMIN);
    }
}