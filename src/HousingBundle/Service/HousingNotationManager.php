<?php

namespace HousingBundle\Service;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use AtypikHouseBundle\Service\ReservationManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use HousingBundle\Entity\Housing;
use HousingBundle\Entity\HousingDetail;
use HousingBundle\Entity\HousingNotation;
use HousingBundle\Entity\HousingNotationType;
use HousingBundle\Entity\HousingNotationValue;
use JMS\Serializer\Serializer;
use League\Uri\Components\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use UserBundle\Entity\User;

/**
 * Housing Manager Service
 */
class HousingNotationManager
{
    /** @var EntityManager $em */
    private $em;

    /** @var Security */
    private $security;

    /** @var HousingManager */
    private $housingManager;

    /** @var ReservationManager */
    private $reservationManager;

    /**
     * HousingManager constructor.
     *
     * @param EntityManager      $em                 Entity manager argument
     * @param Security           $security           Security context
     * @param HousingManager     $housingManager     Get housing manager
     * @param ReservationManager $reservationManager Get the reservation manager
     */
    public function __construct(EntityManager $em, Security $security, HousingManager $housingManager, ReservationManager $reservationManager)
    {
        $this->em = $em;
        $this->security = $security;
        $this->housingManager = $housingManager;
        $this->reservationManager = $reservationManager;
    }

    /**
     * Made some check and return the housing reviews for action
     *
     * @param string $slug Get the slug housing
     *
     * @return HousingNotation
     *
     * @throws \InvalidArgumentException
     * @throws \League\Uri\Components\Exception
     * @throws   \ErrorException
     */
    public function getHousingNotationUser(string $slug)
    {
        try {
            $this->em->getFilters()->enable('deleted');

            $housing = $this->housingManager->getHousingEntity($slug);
            $reservation = $this->reservationManager->getUserHousingReservation($housing);
            $this->checkIfReviewPossible($reservation);
        } catch (Exception $e) {
            throw $e;
        }

        return $this->createNewNotation($reservation);
    }

    /**
     * Made some check and return All Housings Reviews for action
     *
     * @return HousingNotation[]
     */
    public function getAllHousingNotationEntity()
    {
        $filter = [];
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->em->getFilters()->disable('deleted');
            return $this->em->getRepository(HousingNotation::class)->findAll();
        }

        return $this->em->getRepository(HousingNotation::class)->findAll($filter);
    }

    /**
     * Create a new notation for a reservation
     *
     * @param Reservation $reservation Get the reservation
     *
     * @return HousingNotation
     */
    private function createNewNotation(Reservation $reservation)
    {
        $this->em->getFilters()->enable('deleted');

        $review = new HousingNotation();
        $review
            ->setReservation($reservation);
        $typeReviews = $this->em->getRepository(HousingNotationType::class)->findAll();
        foreach ($typeReviews as $type) {
            $value = new HousingNotationValue();
            $value
                ->setNotationType($type);
            $review->addValue($value);
        }

        return $review;
    }

    /**
     * Check if this reservation can be reviewed or not
     *
     * @param Reservation $reservation Get the reservation
     *
     * @return void
     *
     * @throws AccessDeniedException
     */
    private function checkIfReviewPossible(Reservation $reservation): void
    {
        if (null === $reservation) {
            throw new AccessDeniedException("You don't have any reservation on this house");
        }
        if ($reservation->getReview()) {
            throw new AccessDeniedException('You already reviewed this home');
        }
        if (ReservationStateEnum::DONE !== $reservation->getState()) {
            throw new AccessDeniedException("You don't have permissions to comment this home");
        }
        $endDate = $reservation->getReservationInfos()->getEndDate();
        $today = new \DateTime();
        if ($endDate->getTimestamp() > $today->getTimestamp()) {
            throw new AccessDeniedException('You will be allowed to review only at the end of the stay');
        }
    }

    /**
     * Get notation score average
     *
     * @param HousingNotation $notation Get the target notation to average
     *
     * @return float|int
     */
    public function getAverage(HousingNotation $notation)
    {
        $total = 0;
        $coeff = 0;
        foreach ($notation->getValues() as $value) {
            $total += $value->getValue();
            $coeff++;
        }
        return $total / $coeff;
    }

    /**
     * Get notation worst part label
     *
     * @param HousingNotation $notation Get the target notation to average
     *
     * @return string
     */
    public function getWorstPart(HousingNotation $notation)
    {
        $worst = '';
        foreach ($notation->getValues() as $value) {
            if ($value->getValue() < 3) {
                $worst .= $value->getNotationType()->getName().', ';
            }
        }

        return substr($worst, 0, -2);
        ;
    }

    /**
     * Get notation best part label
     *
     * @param HousingNotation $notation Get the target notation to average
     *
     * @return string
     */
    public function getBestPart(HousingNotation $notation)
    {
        $best = '';
        foreach ($notation->getValues() as $value) {
            if ($value->getValue() >= 3) {
                $best .= $value->getNotationType()->getName().', ';
            }
        }

        return substr($best, 0, -2);
        ;
    }

    /**
     * Get the average review score for housing
     *
     * @param Housing $housing get the housing
     *
     * @return float|int
     */
    public function getAverageHousingScore(Housing $housing)
    {
        return $this->em->getRepository(Housing::class)->getHouseAverageScore($housing->getId());
    }

    /**
     * Get the best notation for housing
     *
     * @param Housing $housing get the housing
     *
     * @return array
     */
    public function getBestHousingNotation(Housing $housing)
    {
        return $this->em->getRepository(Housing::class)->getBestNotationHouse($housing->getId());
    }
}
