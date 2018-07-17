<?php

namespace AtypikHouseBundle\Service;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use PaymentBundle\Entity\MoneyMovement;
use PaymentBundle\Entity\PaymentInfos;
use PaymentBundle\Enum\MoneyMovementStateEnum;
use PaymentBundle\Enum\PaymentStateEnum;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use UserBundle\Entity\User;

/**
 * Reservation Manager Service
 */
class ReservationManager
{
    /**
     *
     * @var Serializer $serializer
     */
    private $serializer;
    /**
     *
     * @var float $fees
     */
    private $fees;
    /**
     *
     * @var EntityManager $em
     */
    private $em;
    /**
     *
     * @var Security
     */
    private $security;

    /**
     * ReservationManager constructor.
     *
     * @param Serializer    $serializer Tools for serialize object
     * @param EntityManager $em         Entity manager argument
     * @param float         $fees       Global AtypikHouse Fees
     * @param Security      $security   Security context
     */
    public function __construct(Serializer $serializer, EntityManager $em, float $fees, Security $security)
    {
        $this->serializer = $serializer;
        $this->fees = $fees;
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * Create an Reservation object from Json string
     *
     * @param string $data Json
     *
     * @return Reservation
     */
    public function createReservationFromJson(string $data)
    {
        return $this->serializer->deserialize($data, Reservation::class, 'json');
    }

    /**
     * Manage the reservation according to the reservation's state
     *
     * @param Reservation $reservation The reservation
     *
     * @return void
     */
    public function manageStateReservationNotification(Reservation $reservation)
    {
        switch ($reservation->getState()) {
            case ReservationStateEnum::PENDING:
                break;
            case ReservationStateEnum::VALIDATED:
                break;
            case ReservationStateEnum::REFUSED:
                break;
            case ReservationStateEnum::CANCELED:
                break;
            case ReservationStateEnum::DONE:
                break;
        }
    }

    /**
     * Made some check and return the reservation for action
     *
     * @param int $id Get the id reservation
     *
     * @return Reservation
     *
     * @throws   \ErrorException
     * @internal param User $user Get the connected User
     */
    public function getReservationEntity(int $id)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
        }
        $reservation = $this->em->getRepository('AtypikHouseBundle:Reservation')->findOneBy(['id' => $id]);

        if ($reservation === null) {
            throw new \ErrorException(sprintf('This reservation %d does not exist', $id));
        }

        if (!$this->security->isGranted('ROLE_PROPRIETARY') && $this->security->getUser() !== $reservation->getUser()) {
            throw new AccessDeniedException('You are not allowed to see this reservation');
        }
        return $reservation;
    }

    /**
     * Made some check and return All reserservation for action
     *
     * @return Reservation[]
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function getAllReservationEntity()
    {
        $filter = [];
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
            return $this->em->getRepository('AtypikHouseBundle:Reservation')->findAll();
        }
        if (!$this->security->isGranted('ROLE_ADMIN') && $this->security->isGranted('ROLE_PROPRIETARY')) {
            /**
             *
 * @var User $user
*/
            $user = $this->security->getUser();
            foreach ($user->getHousings() as $housing) {
                $filter['housing'][] = $housing->getId();
            }
            return $this->em->getRepository('AtypikHouseBundle:Reservation')->findBy($filter);
        }

        throw new AccessDeniedException('You don\'t have permissions');
    }

    /**
     * Manage the reservation according to the reservation's state
     *
     * @param Reservation $reservation The reservation
     *
     * @return void
     */
    public function paidReservation(Reservation $reservation)
    {
        $housing = $reservation->getHousing();
        $reservationInfos = $reservation->getReservationInfos();

        $paymentInfo = new PaymentInfos();
        $price = $housing->getPrice() * $reservationInfos->getInterval();
        $finalPrice = $price + ($price * ($this->fees / 100));
        $paymentInfo->setPrice($finalPrice);
        $paymentInfo->setReservation($reservation);

        $moneyMovement = new MoneyMovement();
        $moneyMovement->setState(PaymentStateEnum::CREATED);
        $moneyMovement->setAction(MoneyMovementStateEnum::PAYIN);
        $moneyMovement->setPaymentInfos($paymentInfo);

        $reservation->setPaymentInfo($paymentInfo);
    }

    /**
     *
     * @param Reservation $reservation Reservation selected
     *
     * @return void
     *
     * @throws \ErrorException
     */
    public function isValidReservation(Reservation $reservation) :void
    {
        $this->isNotBeforeTodayReservation($reservation);
        $this->isHousingAvailable($reservation);
    }

    /**
     *
     * @param Reservation $reservation Reservation selected
     *
     * @return void
     *
     * @throws \ErrorException
     */
    public function isNotBeforeTodayReservation(Reservation $reservation) :void
    {
        $now = new \DateTime();
        $startReservationDate = $reservation->getReservationInfos()->getStartDate();
        $endReservationDate = $reservation->getReservationInfos()->getEndDate();
        if ($startReservationDate <= $now) {
            throw new \ErrorException(sprintf('Reservation start date can not be reserved in the past'));
        }
        if ($endReservationDate <= $now) {
            throw new \ErrorException(sprintf('Reservation end date can not be reserved in the past'));
        }
    }

    /**
     *
     * @param Reservation $reservation Reservation selected
     *
     * @return void
     *
     * @throws \ErrorException
     */
    public function isHousingAvailable(Reservation $reservation) :void
    {
        $startReservationDate = $reservation->getReservationInfos()->getStartDate();
        $endReservationDate = $reservation->getReservationInfos()->getEndDate();
        $housingUndisponibilities = $reservation->getHousing()->getUndisponibility();

        foreach ($housingUndisponibilities as $undisponibility) {
            if ($startReservationDate == $undisponibility->getStartDate()) {
                throw new \ErrorException(sprintf('Housing is not available'));
            }
            if ($endReservationDate == $undisponibility->getStartDate()) {
                throw new \ErrorException(sprintf('Housing is not available'));
            }
            if ($undisponibility->getStartDate() >= $startReservationDate && $endReservationDate <= $undisponibility->getStartDate()) {
                throw new \ErrorException(sprintf('Housing is not available'));
            }
        }
    }
}
