<?php

namespace AtypikHouseBundle\Service;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Entity\ReservationInfos;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use Doctrine\ORM\EntityManager;
use HousingBundle\Entity\Housing;
use JMS\Serializer\Serializer;
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
        $this->em->getFilters()->enable('deleted');
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
        }
        $reservation = $this->em->getRepository('AtypikHouseBundle:Reservation')->findOneBy(['id' => $id]);

        if ($reservation === null) {
            throw new \ErrorException(sprintf('This reservation %d does not exist', $id));
        }

        return $reservation;
    }

    /**
     * Return All reserservations of an user
     *
     * @return Reservation[]
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function getUserReservations()
    {
        $this->em->getFilters()->enable('deleted');
        return $this->em->getRepository('AtypikHouseBundle:Reservation')->findBy(['user' => $this->security->getUser()]);
    }

    /**
     * Return All reserservations of an user
     *
     * @param Housing $housing Get the housing targeted
     *
     * @return Reservation
     */
    public function getUserHousingReservation(Housing $housing)
    {
        $this->em->getFilters()->enable('deleted');
        return $this->em->getRepository('AtypikHouseBundle:Reservation')->findOneBy(['user' => $this->security->getUser(), 'housing' => $housing]);
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
        $this->em->getFilters()->enable('deleted');
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
            return $this->em->getRepository('AtypikHouseBundle:Reservation')->findAll();
        }
        if (!$this->security->isGranted('ROLE_ADMIN') && $this->security->isGranted('ROLE_PROPRIETARY')) {
            /** @var User $user */
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

    /**
     * Return the price for a stay depending on reservation info
     *
     * @param Reservation $reservation Get the reservation
     *
     * @return float
     */
    public function getPriceForStay(Reservation $reservation)
    {
        return $reservation->getHousing()->getPrice() * $reservation->getReservationInfos()->getInterval();
    }

    /**
     * Return the price for a stay depending on reservation info
     *
     * @param Reservation $reservation Get the reservation
     *
     * @return float
     */
    public function getTotalPriceForStay(Reservation $reservation)
    {
        return $this->getPriceForStay($reservation) + $this->getTaxForStay($reservation);
    }

    /**
     * Return the price for a stay depending on reservation info
     *
     * @param Reservation $reservation Get the reservation
     *
     * @return float
     */
    public function getTotalPriceForStayPaypal(Reservation $reservation)
    {
        return $this->getTotalPriceForStay($reservation) * 100;
    }

    /**
     * Return the tax for a stay depending on reservation info
     *
     * @param Reservation $reservation Get the reservation
     *
     * @return float
     */
    public function getTaxForStay(Reservation $reservation)
    {
        return $this->getPriceForStay($reservation) * ($this->fees / 100);
    }


    /**
     * Get interval between start and en date
     *
     * @param ReservationInfos $reservationInfos Targeted reservation Info
     *
     * @return array
     */
    public function getIntervalArrayDate(ReservationInfos $reservationInfos)
    {
        $dates = [];
        while ($reservationInfos->getStartDate()->diff($reservationInfos->getEndDate())->d) {
            $dates[] = $reservationInfos->getStartDate()->format('d/m/Y');
            $reservationInfos->getStartDate()->add(new \DateInterval('P1D'));
        }
        $dates[] = $reservationInfos->getStartDate()->format('d/m/Y');
        return $dates;
    }

    /**
     * Create an new reservation for an user
     *
     * @return Reservation
     */
    public function createAdminReservation()
    {
        $reservation = new Reservation();
        $reservation->setState(ReservationStateEnum::CREATED);
        return $reservation;
    }

    /**
     * Create an new reservation for an user
     *
     * @param Housing $housing Get the targeted housing for reservation
     *
     * @return Reservation
     */
    public function createReservation(Housing $housing)
    {
        $reservation = new Reservation();
        $reservation->setState(ReservationStateEnum::CREATED);
        $reservation->setHousing($housing);
        $reservation->setUser($this->security->getUser());
        return $reservation;
    }

    /**
     * Return All reservations for a proprietary for action
     *
     * @return Housing[]
     */
    public function getAllReservationProprietaryEntity()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $this->em->getFilters()->enable('deleted');

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
        }

        return $this->em->getRepository(Reservation::class)->getProprietaryReservation($user->getId());
    }

    /**
     * Init first payment infos
     *
     * @param Reservation $reservation Get the targeted reservation
     *
     * @return PaymentInfos
     */
    public function createPaymentInfos(Reservation $reservation)
    {
        $paymentInfos = new PaymentInfos();
        $paymentInfos->setReservation($reservation);
        $paymentInfos->setPrice($this->getTotalPriceForStay($reservation));

        return $paymentInfos;
    }

    /**
     * Check if most important user information is completed
     *
     * @return bool
     */
    public function hasCompleteProfile()
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user->getPersonalInfos()) {
            return false;
        }

        if (null === $user->getPersonalInfos()->getFirstname() || null === $user->getPersonalInfos()->getLastname()) {
            return false;
        }

        if (null === $user->getPersonalInfos()->getAddress()) {
            return false;
        }

        return true;
    }

    /**
     * Get all unavailability of an housing cause of calendar and reservation
     *
     * @param Housing     $housing     Get the targeted Housing
     * @param Reservation $reservation Get the targeted reservation if set
     *
     * @return array
     */
    public function getUndisponibility(Housing $housing, Reservation $reservation = null)
    {
        $unavailability = [];
        $houseUnavailability = $housing->getUndisponibility();
        foreach ($houseUnavailability as $undisponibility) {
            if ($undisponibility->getStartDate()) {
                $unavailability[] = $undisponibility->getStartDate()->format('d/m/Y');
            }
        }

        $reservations = $housing->getReservations();
        foreach ($reservations as $item) {
            if ($reservation && $item->getId() === $reservation->getId()) {
                continue;
            }
            if ($item->getReservationInfos()) {
                $unavailability = array_merge($unavailability, $this->getIntervalArrayDate($item->getReservationInfos()));
            }
        }
        return $unavailability;
    }
}
