<?php

namespace App\Security\Voter;

use App\Entity\Company;
use App\Entity\Offer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class OfferVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['LIST_OFFERS', 'SHOW'])
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
            case 'SHOW':
                return $user === $offer->getCompany()->getUser();
                break;
//            case 'SHOW_OFFER':
//                $company->getUser();
//                return $user === $company->getUser();
//                break;
        }
        
        return false;
    }
}
