<?php

namespace App\Security\Voter;

use App\Entity\Company;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CompanyVoter extends Voter
{
    const VIEW = 'companyView';
    const COMPANY_ADMIN = 'companyAdmin';
    const OFFER_EDIT = 'offerEdit';

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW, self::COMPANY_ADMIN, self::OFFER_EDIT])
            && $subject instanceof Company;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        $company=$subject;
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
                return $user === $company->getUser() || $company->getMembers()->contains($user);
                break;
            case self::COMPANY_ADMIN:
                return $user === $company->getUser();
                break;
        }
        
        return false;
    }
}
