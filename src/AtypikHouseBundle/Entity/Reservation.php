<?php

namespace AtypikHouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HousingBundle\Entity\Housing;
use PaymentBundle\Entity\PaymentInfos;
use ToolsBundle\DataTrait\DateTrait;
use ToolsBundle\DataTrait\DeletedDateTrait;
use UserBundle\Entity\User;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="AtypikHouseBundle\Repository\ReservationRepository")
 */
class Reservation
{
    use DateTrait;
    use DeletedDateTrait;

    /**
     *
     * @var int
     *
     * @ORM\Column(name="id",               type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\Housing", inversedBy="reservations")
     * @ORM\JoinColumn(name="housing_id",                          referencedColumnName="id")
     */
    private $housing;

    /**
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="reservations")
     * @ORM\JoinColumn(name="user_id",                       referencedColumnName="id")
     */
    private $user;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=45)
     */
    private $state;

    /**
     *
     * @ORM\OneToOne(targetEntity="PaymentBundle\Entity\PaymentInfos", mappedBy="reservation")
     */
    private $paymentInfo;

    /**
     *
     * @ORM\OneToOne(targetEntity="AtypikHouseBundle\Entity\ReservationInfos", mappedBy="reservation", )
     * @ORM\JoinColumn(name="reservation_infos",                               referencedColumnName="id")
     */
    private $reservationInfos;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     *
     * @return Housing
     */
    public function getHousing()
    {
        return $this->housing;
    }

    /**
     *
     * @param mixed $housing Set the housing for reservation
     *
     * @return void
     */
    public function setHousing($housing)
    {
        $this->housing = $housing;
    }

    /**
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @param User $user Set the user for reservation
     *
     * @return void
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     *
     * @param string $state Set the state for reservation
     *
     * @return void
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     *
     * @return null|PaymentInfos
     */
    public function getPaymentInfo()
    {
        return $this->paymentInfo;
    }

    /**
     *
     * @param null|PaymentInfos $paymentInfo Set the pamentInfos for reservation
     *
     * @return void
     */
    public function setPaymentInfo(?PaymentInfos $paymentInfo)
    {
        $this->paymentInfo = $paymentInfo;
    }

    /**
     *
     * @return ReservationInfos
     */
    public function getReservationInfos()
    {
        return $this->reservationInfos;
    }

    /**
     *
     * @param mixed $reservationInfos Set the reservationInfos for reservation
     *
     * @return void
     */
    public function setReservationInfos($reservationInfos)
    {
        $this->reservationInfos = $reservationInfos;
    }
}
