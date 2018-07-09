<?php

namespace HousingBundle\Listener;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Service\ReservationAdminManager;
use AtypikHouseBundle\Service\ReservationManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use HousingBundle\Entity\Housing;
use HousingBundle\Service\HousingManager;

/**
 * Reservation Admin Manager Service
 */
class HousingListener
{
    /**
     * @var HousingManager $housingManager
     */
    private $housingManager;

    /**
     * ReservationListener constructor.
     *
     * @param HousingManager $housingManager
     */
    public function __construct(HousingManager $housingManager)
    {
        $this->housingManager = $housingManager;
    }

    /**
     *
     * @param PreUpdateEventArgs|LifecycleEventArgs $args Get the PreUpdateEventArgs
     *
     * @return void
     *
     * @ORM\PreUpdate
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $housing = $args->getEntity();

        if ($housing instanceof Housing) {
        }
    }
}
