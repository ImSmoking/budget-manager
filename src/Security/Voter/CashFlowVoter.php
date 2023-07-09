<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Constant\UserRoles;
use App\Entity\CashFlow;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CashFlowVoter extends Voter
{
    const VIEW = 'view';
    const DELETE = 'delete';
    const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::DELETE, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof CashFlow) {
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

        /** @var CashFlow $cashFlow */
        $cashFlow = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($cashFlow, $user),
            self::EDIT => $this->canEdit($cashFlow, $user),
            self::DELETE => $this->canDelete($cashFlow, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(CashFlow $cashFlow, User $user): bool
    {
        return $this->canAccess($cashFlow, $user);
    }

    private function canEdit(CashFlow $cashFlow, User $user): bool
    {
        return $this->canAccess($cashFlow, $user);
    }

    private function canDelete(CashFlow $cashFlow, User $user): bool
    {
        return $this->canAccess($cashFlow, $user);
    }

    private function canAccess(CashFlow $cashFlow, User $user): bool
    {

        if ($cashFlow->getWallet()->getUser() === $user) {
            return true;
        }

        return $user->hasRole(UserRoles::ADMIN);
    }
}