<?php

namespace App\Security\Voter;

use App\Entity\Offer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class OfferVoter extends Voter
{
    const VIEW_ASSO = 'view_asso';
    
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW_ASSO])
            && $subject instanceof Offer;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        
        $offer=$subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW_ASSO:
                return ((null === $offer->getAssociation()) or ($user === $offer->getAssociation()->getUser()));
                break;
        }

        return false;
    }
}
