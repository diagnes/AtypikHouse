<?php

namespace AtypikHouseBundle\Listener;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Service\ReservationAdminManager;
use AtypikHouseBundle\Service\ReservationManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation Admin Manager Service
 */
class ReservationListener
{
    /**
     *
     * @var ReservationManager $reservationManager
     */
    private $reservationManager;

    /**
     * ReservationListener constructor.
     *
     * @param ReservationManager $reservationManager Get the service reservationManager
     */
    public function __construct(ReservationManager $reservationManager)
    {
        $this->reservationManager = $reservationManager;
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
        $reservation = $args->getEntity();
        $change = $args->getEntityChangeSet();

        if ($reservation instanceof Reservation) {
            if (isset($change['state'])) {
                $this->reservationManager->manageStateReservationNotification($reservation);
            }
        }
    }
}
