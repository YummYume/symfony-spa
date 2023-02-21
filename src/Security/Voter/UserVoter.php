<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Enum\UserRoleEnum;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserVoter extends Voter
{
    public const EDIT = 'EDIT_USER';
    public const EDIT_ROLES = 'EDIT_ROLES';
    public const DELETE = 'DELETE_USER';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::EDIT, self::EDIT_ROLES, self::DELETE], true) && $subject instanceof UserInterface;
    }

    /**
     * @var User
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->canEditUser($subject),
            self::EDIT_ROLES => $this->canEditRoles($subject, $user),
            self::DELETE => $this->canDeleteUser($subject),
            default => throw new \LogicException('UserVoter - This code should not be reached.'),
        };
    }

    /**
     * @internal A user can edit another user only if they have the current role as they do (or above)
     */
    private function canEditUser(UserInterface $subject): bool
    {
        return
            $this->security->isGranted(UserRoleEnum::SuperAdmin->value)
            || ($this->security->isGranted(UserRoleEnum::Admin->value) && !\in_array(UserRoleEnum::SuperAdmin->value, $subject->getRoles(), true));
    }

    /**
     * @internal A user can edit another user's roles only if they are a super admin and not editing their own account
     */
    private function canEditRoles(UserInterface $subject, UserInterface $currentUser): bool
    {
        return $this->security->isGranted(UserRoleEnum::SuperAdmin->value) && $subject !== $currentUser;
    }

    /**
     * @internal Only a super admin can delete users. A super admin cannot be deleted
     */
    private function canDeleteUser(UserInterface $subject): bool
    {
        return $this->security->isGranted(UserRoleEnum::SuperAdmin->value) && !\in_array(UserRoleEnum::SuperAdmin->value, $subject->getRoles(), true);
    }
}
