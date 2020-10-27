<?php

namespace App\Security\Voter;

use App\Entity\Company;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports($attribute, $user)
    {
        return in_array($attribute, ['GET_USER', 'DELETE_USER'])
            && $user instanceof \App\Entity\User;
    }

    protected function voteOnAttribute($attribute, $user, TokenInterface $token)
    {
        $company = $token->getUser();

        if (!$company instanceof Company) {
            return false;
        }

        switch ($attribute) {
            case 'GET_USER':
                return $user->getCompany() == $company;

            case 'DELETE_USER':
                return $user->getCompany() == $company;
        }

        return false;
    }
}
