<?php

namespace ToolsBundle\DataFixtures\ORM;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HousingBundle\Entity\Housing;
use HousingBundle\Entity\HousingType;
use UserBundle\Entity\Address;
use UserBundle\Entity\User;
use UserBundle\Entity\UserPersonalInfos;
use UserBundle\Enum\UserGenderEnum;
use UserBundle\Form\PersonalInfoType;

class AdminFixtures implements FixtureInterface
{
    /**
     * @param ObjectManager $manager Get the object manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $address = new Address();
        $address->setAddress('Allée de la butte aux cailles');
        $address->setCity('Noisy le Grand');
        $address->setCountry('France');
        $address->setPostalCode('93160');
        $address->setStreetNumber('4');
        $address->setState('Seine-st-Denis');

        $personalInfos = new UserPersonalInfos();
        $personalInfos->setGender(UserGenderEnum::MAN);
        $personalInfos->setFirstname('Admin');
        $personalInfos->setLastname('AtypikHouse');
        $personalInfos->setAddress($address);
        $personalInfos->setBirthDate(new \DateTime('1991-11-22'));
        $personalInfos->setBirthLocation('Les lillas');
        $personalInfos->setDescription('Oooooh moi tu sais je fais mon boulot d\'admin seulement');
        $personalInfos->setNationality('FR');
        $personalInfos->setPhoneNumber('0612345678');
        $personalInfos->setProfession('Dev');

        // Un admin
        $userAdmin = new User();
        $personalInfos->setUser($userAdmin);
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@atipikhouse.com');
        $userAdmin->setPersonalInfos($personalInfos);
        $userAdmin->setEmailCanonical('admin@atipikhouse.com');
        $userAdmin->setPassword('Admin123');
        $userAdmin->setEnabled(true);
        $userAdmin->setRoles(
            [
            'ROLE_IT',
            'ROLE_USER'
            ]
        );
        $manager->persist($userAdmin);

        $manager->flush();
    }
}