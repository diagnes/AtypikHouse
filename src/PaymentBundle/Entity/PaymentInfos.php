<?php

namespace PaymentBundle\Entity;

use AtypikHouseBundle\Entity\Reservation;
use Doctrine\ORM\Mapping as ORM;
use ToolsBundle\DataTrait\DateTrait;

/**
 * PaymentInfos
 *
 * @ORM\Table(name="payment_infos")
 * @ORM\Entity(repositoryClass="PaymentBundle\Repository\PaymentInfosRepository")
 */
class PaymentInfos
{
    use DateTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity="AtypikHouseBundle\Entity\Reservation", inversedBy="paymentInfo")
     * @ORM\JoinColumn(name="reservation_id", referencedColumnName="id")
     */
    private $reservation;

    /**
     * @ORM\OneToMany(targetEntity="PaymentBundle\Entity\MoneyMovement", mappedBy="paymentInfos")
     */
    private $moneyMovements;

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
     * Get Price
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Set Price
     *
     * @param float $price Price to set
     *
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * Get Reservation
     *
     * @return Reservation
     */
    public function getReservation(): Reservation
    {
        return $this->reservation;
    }

    /**
     * Set Reservation
     *
     * @param Reservation $reservation Reservation to set
     *
     * @return void
     */
    public function setReservation(Reservation $reservation): void
    {
        $this->reservation = $reservation;
    }

    /**
     * Get MoneyMovement
     *
     * @return null|MoneyMovement
     */
    public function getMoneyMovements()
    {
        return $this->moneyMovements;
    }

    /**
     * Set MoneyMovement
     *
     * @param MoneyMovement $moneyMovements MoneyMovement to set
     *
     * @return void
     */
    public function setMoneyMovements(MoneyMovement $moneyMovements): void
    {
        $this->moneyMovements = $moneyMovements;
    }
}
