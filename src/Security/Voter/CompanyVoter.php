<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CompanyVoter extends Voter
{
    protected function supports($attribute, $user)
    {
        return in_array($attribute, ['GET_USER', 'DELETE_USER'])
            && $user instanceof \App\Entity\User;
    }

    protected function voteOnAttribute($attribute, $user, TokenInterface $token)
    {
        $company = $token->getUser();

        if (!$company instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'GET_USER':
                return $user->getCompany()->getid() == $company->getId();

                break;
            case 'DELETE_USER':
                return $user->getCompany()->getid() == $company->getId();

                break;
        }

        return false;
    }
}
