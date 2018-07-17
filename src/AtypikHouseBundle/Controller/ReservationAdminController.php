<?php

namespace AtypikHouseBundle\Controller;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ToolsBundle\Service\DataResponseAdapter;

/**
 * Reservation controller.
 *
 * @Security("has_role('ROLE_PROPRIETARY')")
 */
class ReservationAdminController extends Controller
{
    /**
     * Lists all reservation entities.
     *
     * @return JsonResponse
     */
    public function allAction()
    {
        $dataResonseManager = $this->get('tools.data_response_manager');

        $reservations = $this->get('ah.reservation_manager')->getAllReservationEntity();
        $data = [
            'reservations' => new DataResponseAdapter($reservations, Reservation::class),
        ];

        return new JsonResponse($dataResonseManager->createAdaptedResponseData($data), 200);
    }

    /**
     * Creates a new reservation entity
     *
     * @param Request $request Get the request for this action
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $reservation = new Reservation();
        $form = $this->createForm('AtypikHouseBundle\Form\ReservationType', $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('reservation_show', ['id' => $reservation->getId()]);
        }

        return $this->render(
            'reservation/new.html.twig',
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Reservation $reservation)
    {
        $deleteForm = $this->createDeleteForm($reservation);
        $editForm = $this->createForm('AtypikHouseBundle\Form\ReservationType', $reservation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservation_edit', ['id' => $reservation->getId()]);
        }

        return $this->render(
            'reservation/edit.html.twig',
            [
            'reservation' => $reservation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            ]
        );
    }

    /**
     * Validated admin reservation.
     *
     * @param int $id This the reservation id
     *
     * @return JsonResponse
     */
    public function validateAction(int $id)
    {
        try {
            $reservation = $this->get('ah.reservation_manager')->getReservationEntity($id);
            $em = $this->getDoctrine()->getManager();
            $reservation->setState(ReservationStateEnum::VALIDATED);
            $em->persist($reservation);
            $em->flush();
            return new JsonResponse('Reservation has been validated', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    /**
     * Refused admin reservation.
     *
     * @param int $id This the reservation id
     *
     * @return JsonResponse
     */
    public function refusedAction(int $id)
    {
        try {
            $reservation = $this->get('ah.reservation_manager')->getReservationEntity($id);
            $em = $this->getDoctrine()->getManager();
            $reservation->setState(ReservationStateEnum::REFUSED);
            $em->persist($reservation);
            $em->flush();
            return new JsonResponse('Reservation has been refused', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    /**
     * Deletes a reservation entity.
     *
     * @param int $id This the reservation id
     *
     * @return JsonResponse
     */
    public function deleteAction(int $id): JsonResponse
    {
        try {
            $reservation = $this->get('ah.reservation_manager')->getReservationEntity($id);
            $em = $this->getDoctrine()->getManager();
            $reservation->setState(ReservationStateEnum::CANCELED);
            $reservation->setDeletedAt(new \DateTime());
            $em->persist($reservation);
            $em->flush();
            return new JsonResponse('Reservation has been deleted', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    /**
     * Undelete a reservation entity.
     *
     * @param int $id This the reservation id
     *
     * @return JsonResponse
     */
    public function undeleteAction(int $id): JsonResponse
    {
        try {
            $reservation = $this->get('ah.reservation_manager')->getReservationEntity($id);
            $em = $this->getDoctrine()->getManager();
            $reservation->setState(ReservationStateEnum::CREATED);
            $reservation->setDeletedAt(null);
            $em->persist($reservation);
            $em->flush();
            return new JsonResponse('Reservation has been undeleted', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}
