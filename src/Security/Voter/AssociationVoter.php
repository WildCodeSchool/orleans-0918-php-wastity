<?php

namespace App\Security\Voter;

use App\Entity\Association;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AssociationVoter extends Voter
{
    const VIEW = 'associationView';
    const ASSO_ADMIN = 'associationAdmin';
    
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW, self::ASSO_ADMIN])
            && $subject instanceof Association;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
    
        $association=$subject;
        
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
                return $user === $association->getUser() || $association->getMembers()->contains($user);
                break;
            case self::ASSO_ADMIN:
                return $user === $association->getUser() || $user->getId() == $_POST['_id'];
                break;
        }

        return false;
    }
}
