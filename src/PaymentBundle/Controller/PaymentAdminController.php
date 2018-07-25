<?php

namespace PaymentBundle\Controller;

use PaymentBundle\Entity\PaymentInfos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PaymentAdminController extends Controller
{
    /**
     * Lists all payment entities.
     *
     * @return Response
     *
     * @throws \LogicException
     */
    public function allAction()
    {
        $em = $this->getDoctrine()->getManager();
        $payments = $em->getRepository(PaymentInfos::class)->findAll();

        return $this->render(
            'PaymentBundle:admin:list.html.twig',
            [
            'payments' => $payments,
            'menu_level' => 'payment_admin'
            ]
        );
    }
}