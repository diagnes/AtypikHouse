<?php

namespace AtypikHouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReservationInfos
 *
 * @ORM\Table(name="reservation_infos")
 * @ORM\Entity(repositoryClass="AtypikHouseBundle\Repository\ReservationInfosRepository")
 */
class ReservationInfos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="resident", type="integer")
     */
    private $resident;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date")
     */
    private $endDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\OneToOne(targetEntity="AtypikHouseBundle\Entity\Reservation", inversedBy="reservationInfos", cascade={"persist"})
     * @ORM\JoinColumn(name="reservation_id", referencedColumnName="id")
     */
    private $reservation;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set resident.
     *
     * @param int $resident Set the resident for ReservationInfos
     *
     * @return ReservationInfos
     */
    public function setResident($resident)
    {
        $this->resident = $resident;

        return $this;
    }

    /**
     * Get resident.
     *
     * @return int
     */
    public function getResident()
    {
        return $this->resident;
    }

    /**
     * Set startDate.
     *
     * @param \DateTime $startDate Set the startDate for ReservationInfos
     *
     * @return ReservationInfos
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate.
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate.
     *
     * @param \DateTime $endDate Set the endDate for ReservationInfos
     *
     * @return ReservationInfos
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate.
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set message.
     *
     * @param string|null $message Set the message for ReservationInfos
     *
     * @return ReservationInfos
     */
    public function setMessage($message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get a reservation
     *
     * @return Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * Get a reservation
     *
     * @param Reservation $reservation Set the reservation for ReservationInfos
     *
     * @return void
     */
    public function setReservation($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get interval between start and en date
     *
     * @return int
     */
    public function getInterval()
    {
        return $this->getStartDate()->diff($this->getEndDate())->d;
    }
}
