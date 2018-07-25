<?php

namespace AtypikHouseBundle\Listener;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Entity\ReservationInfos;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use AtypikHouseBundle\Service\ReservationAdminManager;
use AtypikHouseBundle\Service\ReservationManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
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
     * On the flush make actions
     *
     * @param OnFlushEventArgs $eventArgs Get OnFlush args value
     *
     * @return void
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof ReservationInfos) {
                $entity->getReservation()->setState(ReservationStateEnum::PENDING);
                $classMetadata = $em->getClassMetadata(\get_class($entity));
                $em->getUnitOfWork()->computeChangeSet($classMetadata, $entity);
                $em->persist($entity);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args Get the LifecycleEventArgs for persist infos
     *
     * @return void
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Reservation) {
            if ($entity->getReservationInfos()) {
                $entity->setState(ReservationStateEnum::PENDING);
            }
        }
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
