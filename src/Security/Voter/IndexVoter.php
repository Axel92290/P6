<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class IndexVoter extends Voter
{
    public const INDEX = 'index';


    protected function supports(string $attribute, mixed $subject): bool
    {

        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html

        //dd(in_array($attribute, [self::INDEX]), $subject instanceof \App\Entity\User);
        return in_array($attribute, [self::INDEX]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();


        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::INDEX:
                if ($user->isVerified() == '0') {
                    return false;

                } else {
                    return true;
                }
                default:
                    break;
        }

        return false;
    }
}
