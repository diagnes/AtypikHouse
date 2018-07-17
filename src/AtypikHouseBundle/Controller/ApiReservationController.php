<?php

namespace AtypikHouseBundle\Controller;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use AtypikHouseBundle\Form\ReservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ToolsBundle\Service\DataResponseAdapter;

/**
 * Reservation controller.
 *
 * @Security("has_role('ROLE_USER')")
 */
class ApiReservationController extends Controller
{
    /**
     * Creates a new reservation entity.
     *
     * @param Request $request Get symfony Requst parameters
     *
     * @return JsonResponse
     */
    public function newAction(Request $request)
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation, []);
        $em = $this->getDoctrine()->getManager();
        $dataResonseManager = $this->get('tools.data_response_manager');
        $reservationManager = $this->get('ah.reservation_manager');

        $form->handleRequest($request);
        try {
            switch (true) {
                case $request->isMethod('GET'):
                    return new JsonResponse(
                        $dataResonseManager->createAdaptedResponseData(
                            [
                            'form' => new DataResponseAdapter($dataResonseManager->createCustomForm($form->createView()), FormView::class),
                            ]
                        ),
                        200
                    );
                    break;
                case $request->isMethod('POST'):
                    if ($form->isSubmitted()) {
                        $reservationManager->isValidReservation($reservation);
                        $reservation->setState(ReservationStateEnum::PENDING);
                        $em->persist($reservation);
                        $em->flush();
                    }
                    break;
            }
            return new JsonResponse('Well done, your reservation has been created', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
    }

    /**
     * Finds and displays a reservation entity.
     *
     * @param int $id This the reservation id
     *
     * @return JsonResponse
     */
    public function showAction(int $id): JsonResponse
    {
        try {
            $reservation = $this->get('ah.reservation_manager')->getReservationEntity($id);
            $dataResonseManager = $this->get('tools.data_response_manager');
            $data = [
                'reservation' => new DataResponseAdapter($reservation, Reservation::class),
            ];

            return new JsonResponse($dataResonseManager->createAdaptedResponseData($data), 200);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    /**
     * Displays a form to edit an existing reservation entity.
     *
     * @param Request $request Get the request symfony parameters
     * @param int     $id      Get the id reservation
     *
     * @return null|JsonResponse
     */
    public function editAction(Request $request, int $id): ?JsonResponse
    {
        try {
            $reservation = $this->get('ah.reservation_manager')->getReservationEntity($id);
            $form = $this->createForm(ReservationType::class, $reservation, []);
            $em = $this->getDoctrine()->getManager();
            $dataResonseManager = $this->get('tools.data_response_manager');

            $form->handleRequest($request);
            switch (true) {
                case $request->isMethod('GET'):
                    $data = [
                        'form' => new DataResponseAdapter($form->createView(), Reservation::class)
                    ];
                    return new JsonResponse($dataResonseManager->createAdaptedResponseData($data), 201);
                    break;
                case $request->isMethod('POST'):
                    if (!$form->isSubmitted()) {
                        $reservation = $this->get('ah.reservation_manager')->createReservationFromJson($request->getContent());
                    }
                    break;
            }
            $em->persist($reservation);
            $em->flush();
            return new JsonResponse('Reservation has been modify', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
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
            $reservation->setState(ReservationStateEnum::VALIDATED_CLIENT);
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
            $reservation->setState(ReservationStateEnum::REFUSED_CLIENT);
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
     * @param int $id Get the id reservation
     *
     * @return JsonResponse
     */
    public function canceledAction(int $id): JsonResponse
    {
        try {
            $reservation = $this->get('ah.reservation_manager')->getReservationEntity($id);
            $em = $this->getDoctrine()->getManager();
            $reservation->setState(ReservationStateEnum::CANCELED);
            $reservation->setDeletedAt(new \DateTime());
            $em->persist($reservation);
            $em->flush();
            return new JsonResponse('', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}
