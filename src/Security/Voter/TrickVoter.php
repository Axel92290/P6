<?php


namespace App\Security\Voter;

use App\Entity\Tricks;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TrickVoter extends Voter
{
    public const EDIT   = 'TRICK_EDIT';
    public const DELETE = 'TRICK_DELETE';

    public function __construct(private Security $security) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::EDIT, self::DELETE], true)
            && $subject instanceof Tricks;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var Tricks $trick */
        $trick = $subject;
        $user  = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false; // non connecté
        }

        // Admins : accès total
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }


        $owner = $trick->getUser(); // renvoie ?User
        return $owner && $owner->getId() === $user->getId();
    }
}


