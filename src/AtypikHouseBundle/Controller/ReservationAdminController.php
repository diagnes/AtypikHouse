<?php

namespace AtypikHouseBundle\Controller;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use AtypikHouseBundle\Form\ReservationAdminFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Reservation Admin controller.
 *
 * In this controller admin can manage all reservations
 * Reservation Admin information
 *
 * PHP version 7.1
 *
 * @category  Controller
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 */
class ReservationAdminController extends Controller
{
    /**
     * Lists all reservation entities.
     *
     * @return Response
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @throws AccessDeniedException
     */
    public function allAction()
    {
        $reservations = $this->get('ah.reservation_manager')->getAllReservationEntity();

        return $this->render(
            'AtypikHouseBundle:admin:list.html.twig',
            [
            'reservations' => $reservations
            ]
        );
    }

    /**
     * Creates a new reservation entity
     *
     * @param Request $request Get the request for this action
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reservationManager = $this->get('ah.reservation_manager');
        $reservation = $reservationManager->createAdminReservation();
        $form = $this->createForm(ReservationAdminFormType::class, $reservation, ['new' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('atypikhouse_reservation_admin_edit', ['id' => $reservation->getId()]);
        }

        return $this->render(
            'AtypikHouseBundle:admin:reservation-form.html.twig',
            [
            'reservation' => $reservation,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing reservation entity.
     *
     * @param Request     $request     Get the request for this action
     * @param Reservation $reservation Get the reservation for this action
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Reservation $reservation)
    {
        $form = $this->createForm(ReservationAdminFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('atypikhouse_reservation_admin_edit', ['id' => $reservation->getId()]);
        }

        return $this->render(
            'AtypikHouseBundle:admin:reservation-form.html.twig',
            [
            'reservation' => $reservation,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Validated admin reservation.
     *
     * @param int $id This the reservation id
     *
     * @Security("has_role('ROLE_PROPRIETARY')")
     *
     * @return RedirectResponse
     */
    public function validateAction(int $id): RedirectResponse
    {
        try {
            $reservation = $this->get('ah.reservation_manager')->getReservationEntity($id);
            $em = $this->getDoctrine()->getManager();
            $reservation->setState(ReservationStateEnum::VALIDATED);
            $em->flush();
            $this->get('ah.notification_manager')->userReservationValidatedNotification($reservation);
            $this->get('session')->getFlashBag()->add('success', sprintf('The reservation %d has been validate', $reservation->getId()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atypikhouse_reservation_list_proprietary_reservation');
    }

    /**
     * Refused admin reservation.
     *
     * @param int $id This the reservation id
     *
     * @Security("has_role('ROLE_PROPRIETARY')")
     *
     * @return RedirectResponse
     */
    public function refusedAction(int $id): RedirectResponse
    {
        try {
            $reservation = $this->get('ah.reservation_manager')->getReservationEntity($id);
            $em = $this->getDoctrine()->getManager();
            $reservation->setState(ReservationStateEnum::REFUSED);
            $em->persist($reservation);
            $em->flush();
            $this->get('ah.notification_manager')->userReservationRefusedNotification($reservation);
            $this->get('session')->getFlashBag()->add('success', sprintf('The reservation %d has been validate', $reservation->getId()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atypikhouse_reservation_list_proprietary_reservation');
    }

    /**
     * Deletes a reservation entity.
     *
     * @param int $id This the reservation id
     *
     * @return RedirectResponse
     */
    public function deleteAction(int $id): RedirectResponse
    {
        try {
            $reservation = $this->get('ah.reservation_manager')->getReservationEntity($id);
            $em = $this->getDoctrine()->getManager();
            $reservation->setState(ReservationStateEnum::CANCELED);
            $reservation->setDeletedAt(new \DateTime());
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The reservation %d has been validate', $reservation->getId()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atypikhouse_reservation_list_proprietary_reservation');
    }

    /**
     * Undelete a reservation entity.
     *
     * @param int $id This the reservation id
     *
     * @return RedirectResponse
     */
    public function undeleteAction(int $id): RedirectResponse
    {
        try {
            $reservation = $this->get('ah.reservation_manager')->getReservationEntity($id);
            $em = $this->getDoctrine()->getManager();
            $reservation->setState(ReservationStateEnum::CREATED);
            $reservation->setDeletedAt(null);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The reservation %d has been validate', $reservation->getId()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atypikhouse_reservation_list_proprietary_reservation');
    }
}
