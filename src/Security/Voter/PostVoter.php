<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostVoter extends Voter
{
    public const EDIT = 'POST_EDIT';

    public function __construct( private Security $security)
    {
    }
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT])
            && $subject instanceof \App\Entity\Post;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->security->isGranted('ROLE_ADMIN') || $subject->getAuthor() == $user;
        }

        return false;
    }
}
