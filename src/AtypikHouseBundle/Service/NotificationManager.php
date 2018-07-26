<?php

namespace AtypikHouseBundle\Service;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Entity\ReservationInfos;
use AtypikHouseBundle\Enum\NotificationTypeEnum;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use Doctrine\ORM\EntityManager;
use HousingBundle\Entity\Housing;
use JMS\Serializer\Serializer;
use PaymentBundle\Entity\PaymentInfos;
use PaymentBundle\Enum\MoneyMovementStateEnum;
use PaymentBundle\Enum\PaymentStateEnum;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use UserBundle\Entity\User;
use UserBundle\Entity\UserNotification;

/**
 * Notification Manager Service
 */
class NotificationManager
{
    /** @var EntityManager $em */
    private $em;

    /** @var Security */
    private $security;

    /** @var \Swift_Mailer */
    private $mailer;

    /** @var string */
    private $env;
    /**
     * @var TwigEngine
     */
    private $engine;
    /**
     * @var Router
     */
    private $router;

    /**
     * Notification Manager constructor.
     *
     * @param EntityManager $em       Entity manager argument
     * @param Security      $security Security context
     * @param \Swift_Mailer $mailer   Get mailer service
     * @param string        $env      Get environnement
     * @param TwigEngine    $engine   Get the twig engine
     * @param Router        $router   Get the router to generate url
     */
    public function __construct(EntityManager $em, Security $security, \Swift_Mailer $mailer, string $env, TwigEngine $engine, Router $router)
    {
        $this->em = $em;
        $this->security = $security;
        $this->mailer = $mailer;
        $this->env = $env;
        $this->engine = $engine;
        $this->router = $router;
    }

    /**
     * Create the notification
     *
     * @param User|null $targetedUser Get the targeted user
     * @param Housing   $housing      Get the housing
     * @param string    $message      Get the message
     * @param string    $type         Get the type
     *
     * @return UserNotification
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function generateNotification(User $targetedUser = null, Housing $housing = null, string $message, string $type)
    {
        $notification = new UserNotification();
        $notification
            ->setMessage($message)
            ->setUser($this->security->getUser())
            ->setHousing($housing)
            ->setState(1)
            ->setType($type)
            ->setTargetUser($targetedUser);

        $this->em->persist($notification);
        $this->em->flush();

        return $notification;
    }

    /**
     * Send notification on user registration
     *
     * @return void
     */
    public function userRegisterNotification()
    {
        $this->generateNotification(
            $this->security->getUser(),
            null,
            'Welcome to AtypikHouse',
            NotificationTypeEnum::USER
        );

        /** @var User $user */
        $user = $this->security->getUser();

        $body = $this->engine->render(
            'AtypikHouseBundle:mail:welcome.html.twig',
            [
            'username' => $user->getUsername(),
            'pathBooking' => $this->router->generate('atypikhouse_housing_index', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ]
        );

        $this->sendEmailWithInfos(
            $user->getEmail(),
            $body,
            'Welcome to AtypikHouse'
        );
    }

    /**
     * Send notification on user pro registration
     *
     * @return void
     */
    public function userRegisterProNotification()
    {
        $this->generateNotification(
            $this->security->getUser(),
            null,
            'Welcome to AtypikHouse Pro',
            NotificationTypeEnum::USER
        );

        /** @var User $user */
        $user = $this->security->getUser();

        $body = $this->engine->render(
            'AtypikHouseBundle:mail:welcome-pro.html.twig',
            [
            'username' => $user->getUsername(),
            'pathAddHouse' => $this->router->generate('atypikhouse_housing_list_proprietary_housing', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ]
        );

        $this->sendEmailWithInfos(
            $user->getEmail(),
            $body,
            'Welcome to AtypikHouse Pro'
        );
    }

    /**
     * Send notification on user reservation
     *
     * @param Reservation $reservation Get the reservation
     *
     * @return void
     */
    public function userStartReservationNotification(Reservation $reservation)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $this->startReservationForUser($reservation, $user);
        $this->startReservationForProprietary($reservation, $user);
    }

    /**
     * Send notification on user reservation paid
     *
     * @param Reservation $reservation Get the reservation
     *
     * @return void
     */
    public function userPaidReservationNotification(Reservation $reservation)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $this->paidReservationForUser($reservation, $user);
        $this->paidReservationForProprietary($reservation, $user);
    }

    /**
     * Send notification on user reservation validation
     *
     * @param Reservation $reservation Get the reservation
     *
     * @return void
     */
    public function userReservationValidatedNotification(Reservation $reservation): void
    {
        $message = sprintf(
            'Bingo : Your reservation for the house %s has been validated',
            $reservation->getHousing()->getTitle()
        );

        $this->generateNotification(
            $reservation->getUser(),
            null,
            $message,
            NotificationTypeEnum::RESERVATION
        );
    }

    /**
     * Send notification on user reservation refused
     *
     * @param Reservation $reservation Get the reservation
     *
     * @return void
     */
    public function userReservationRefusedNotification(Reservation $reservation): void
    {
        $message = sprintf(
            'Ooooh.. : Your reservation for the house %s has been refused',
            $reservation->getHousing()->getTitle()
        );

        $this->generateNotification(
            $reservation->getUser(),
            null,
            $message,
            NotificationTypeEnum::RESERVATION
        );
    }

    /**
     * Send notification on user ask for validation
     *
     * @param Housing $housing Get the housing
     *
     * @return void
     */
    public function askForValidationNotification(Housing $housing): void
    {
        $message = sprintf(
            'Let\'s work: %s ask for a validation of his house %s',
            $housing->getProprietary()->getUsername(),
            $housing->getTitle()
        );

        $admins = $this->em->getRepository(User::class)->findByRole('ROLE_ADMIN');
        /** @var User $admin */
        foreach ($admins as $admin) {
            $this->generateNotification(
                $admin,
                null,
                $message,
                NotificationTypeEnum::HOUSING
            );
        }
    }

    /**
     * Send notification on user registration
     *
     * @param Housing $housing Get the housing
     *
     * @return void
     */
    public function userAskForValidationValidated(Housing $housing): void
    {
        $message = sprintf(
            'Good News: Your request has been validated. Your house %s is now online',
            $housing->getTitle()
        );

        $this->generateNotification(
            $housing->getProprietary(),
            null,
            $message,
            NotificationTypeEnum::HOUSING
        );
    }

    /**
     * Send notification on user registration
     *
     * @param Housing $housing Get the housing
     *
     * @return void
     */
    public function userAskForValidationRefused(Housing $housing): void
    {
        $message = sprintf(
            'Bad News: Your request for the house %s has been refused. Please contact us for more information',
            $housing->getTitle()
        );

        $this->generateNotification(
            $housing->getProprietary(),
            null,
            $message,
            NotificationTypeEnum::HOUSING
        );
    }

    /**
     * Send email with this infos in params
     *
     * @param string $userEmail Get the user receiver mail
     * @param string $body      Get the body
     * @param string $subject   Get the subject
     * @param string $from      Get the user sender mail
     *
     * @return void
     */
    private function sendEmailWithInfos(string $userEmail, string $body, string $subject, string $from = 'team@atypikhouse.com'): void
    {
        $setTo = ('dev' === $this->env) ? 'diagne.stephane@gmail.com' : $userEmail;

        $message = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setTo($setTo)
            ->setBody(
                $body,
                'text/html'
            );
        $this->mailer->send($message);
    }

    /**
     * Create notification for user who start reservation
     *
     * @param Reservation $reservation Get the reservation
     * @param User        $user        Get the connected user
     *
     * @return void
     */
    private function startReservationForUser(Reservation $reservation, User $user): void
    {
        $message = sprintf(
            'Your reservation for the house %s has been completed wait for %s validation',
            $reservation->getHousing()->getTitle(),
            $reservation->getHousing()->getProprietary()->getUsername()
        );

        $this->generateNotification(
            $user,
            null,
            $message,
            NotificationTypeEnum::RESERVATION
        );
    }

    /**
     * Create notification for proprietary who start reservation
     *
     * @param Reservation $reservation Get the reservation
     * @param User        $user        Get the connected user
     *
     * @return void
     */
    private function startReservationForProprietary(Reservation $reservation, $user): void
    {
        $message = sprintf(
            'Hey Hey %s has completed is reservation for %s and wait for validation',
            $user->getUsername(),
            $reservation->getHousing()->getTitle()
        );

        $this->generateNotification(
            $reservation->getHousing()->getProprietary(),
            $reservation->getHousing(),
            $message,
            NotificationTypeEnum::RESERVATION
        );
    }

    /**
     * Create notification for user who start reservation
     *
     * @param Reservation $reservation Get the reservation
     * @param User        $user        Get the connected user
     *
     * @return void
     */
    private function paidReservationForUser(Reservation $reservation, User $user): void
    {
        $message = sprintf(
            'Your reservation for %s is now paid and start %s to %s. Atypikhouse wish you a good trip',
            $reservation->getHousing()->getTitle(),
            $reservation->getReservationInfos()->getStartDate()->format('d/m/Y'),
            $reservation->getReservationInfos()->getEndDate()->format('d/m/Y')
        );

        $this->generateNotification(
            $user,
            null,
            $message,
            NotificationTypeEnum::RESERVATION
        );
    }

    /**
     * Create notification for proprietary who start reservation
     *
     * @param Reservation $reservation Get the reservation
     * @param User        $user        Get the connected user
     *
     * @return void
     */
    private function paidReservationForProprietary(Reservation $reservation, $user): void
    {
        $message = sprintf(
            'Another one: %s has paid is reservation for %d nights in %s for %.2f. Make them trip faboulous',
            $user->getUsername(),
            $reservation->getReservationInfos()->getInterval(),
            $reservation->getHousing()->getTitle(),
            $reservation->getPaymentInfo()->getPaypalAmount()
        );

        $this->generateNotification(
            $reservation->getHousing()->getProprietary(),
            $reservation->getHousing(),
            $message,
            NotificationTypeEnum::RESERVATION
        );
    }
}
