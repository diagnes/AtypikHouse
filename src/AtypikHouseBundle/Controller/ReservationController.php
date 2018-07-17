<?php

namespace AtypikHouseBundle\Controller;

use AtypikHouseBundle\Entity\Reservation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reservation controller.
 */
class ReservationController extends Controller
{
    /**
     * Lists all reservation entities.
     *
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository('AtypikHouseBundle:Reservation')->findAll();

        return $this->render(
            'reservation/index.html.twig',
            [
            'reservations' => $reservations,
            ]
        );
    }
}
