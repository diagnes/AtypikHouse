<?php

namespace AtypikHouseBundle\Controller;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
