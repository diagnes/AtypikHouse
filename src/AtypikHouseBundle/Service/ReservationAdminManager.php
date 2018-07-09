<?php

namespace AtypikHouseBundle\Service;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use Buzz\Message\Response;
use JMS\Serializer\Serializer;
use PaymentBundle\Entity\MoneyMovement;
use PaymentBundle\Entity\PaymentInfos;
use PaymentBundle\Enum\MoneyMovementStateEnum;
use PaymentBundle\Enum\PaymentStateEnum;

/**
 * Reservation Admin Manager Service
 */
class ReservationAdminManager
{
    /**
     * @var Serializer
     */
    private $serializer;
    /**
     * @var float
     */
    private $fees;

    /**
     * ReservationManager constructor.
     *
     * @param Serializer $serializer Tools for serialize object
     * @param float      $fees       Global AtypikHouse Fees
     */
    public function __construct(Serializer $serializer, float $fees)
    {
        $this->serializer = $serializer;
        $this->fees = $fees;
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
}
