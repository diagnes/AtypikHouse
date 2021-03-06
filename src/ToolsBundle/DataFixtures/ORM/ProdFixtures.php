<?php

namespace ToolsBundle\DataFixtures\ORM;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HousingBundle\Entity\Housing;
use HousingBundle\Entity\HousingType;
use HousingBundle\Enum\HousingStateEnum;
use UserBundle\Entity\Address;
use UserBundle\Entity\User;

class ProdFixtures implements FixtureInterface
{
    /**
     * @param ObjectManager $manager Get the object manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // Un admin
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@atipikhouse.com');
        $userAdmin->setEmailCanonical('admin@atipikhouse.com');
        $userAdmin->setPlainPassword('Azerty123');
        $userAdmin->setEnabled(true);
        $userAdmin->setRoles(
            [
            'ROLE_IT',
            'ROLE_USER'
            ]
        );
        $manager->persist($userAdmin);

        $adress = new Address();
        $adress->setAddress('Allée de la colline');
        $adress->setCity('Paris');
        $adress->setCountry('France');
        $adress->setPostalCode('75001');
        $adress->setStreetNumber('4');
        $adress->setState('Ile de France');
        $manager->persist($adress);

        $housingType = new HousingType();
        $housingType->setName('Maison');
        $housingType->setDescription('Une petite description et voila');
        $manager->persist($housingType);

        $housing = new Housing();
        $housing->setAddress($adress);
        $housing->setState(HousingStateEnum::CREATED);
        $housing->setPrice(99);
        $housing->setVisible(false);
        $housing->setMaxResident(3);
        $housing->setType($housingType);
        $housing->setProprietary($userAdmin);
        $housing->setTitle('La casa de papel');
        $manager->persist($housing);

        $reservation = new Reservation();
        $reservation->setState(ReservationStateEnum::PENDING);
        $reservation->setHousing($housing);
        $reservation->setUser($userAdmin);
        $manager->persist($reservation);

        $manager->flush();
    }
}