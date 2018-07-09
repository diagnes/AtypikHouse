<?php

namespace ToolsBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use UserBundle\Entity\User;
use UserBundle\Entity\UserPersonalInfos;

class UserInfos
{
    /**
     *
     * @param User                       $user     Get User
     * @param UserResponseInterface|null $response Get the user response interface
     *
     * @return User
     */
    public function setPersonalInfo(User $user, UserResponseInterface $response = null)
    {
        $personalInfos = $user->getPersonalInfos() ?? new UserPersonalInfos();
        if (null !== $response) {
            $personalInfos->setFirstName($response->getFirstName());
            $personalInfos->setLastName($response->getLastName());
        }

        $user->setPersonalInfos($personalInfos);

        return $user;
    }
}