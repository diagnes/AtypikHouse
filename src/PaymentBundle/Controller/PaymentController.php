<?php

namespace PaymentBundle\Controller;

use AtypikHouseBundle\Enum\ReservationStateEnum;
use PaymentBundle\Enum\PaymentStateEnum;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Payum\Core\Storage\StorageInterface;
use Payum\Core\Security\TokenInterface;
use PaymentBundle\Entity\PaymentInfos;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * Prepare the payment for reservation via Paypal
     *
     * @param int $id reservation
     *
     * @return RedirectResponse
     *
     * @throws \ErrorException
     */
    public function prepareAction(int $id)
    {
        $gatewayName = 'paypal_express_checkout';

        $reservationManager = $this->get('ah.reservation_manager');
        $reservation = $reservationManager->getReservationEntity($id);
        $user = $reservation->getUser();

        /** @var StorageInterface $storage */
        $storage = $this->get('payum')->getStorage(PaymentInfos::class);

        /** @var PaymentInfos $payment */
        $payment = $storage->create();
        $payment->setReservation($reservation);
        $payment->setNumber(uniqid('atypikPay', true));
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount($reservationManager->getTotalPriceForStayPaypal($reservation));
        $payment->setDescription(
            sprintf(
                'The payment for reservation %d of %s the %s',
                $reservation->getId(),
                (null !== $user->getPersonalInfos()) ? $user->getPersonalInfos()->getFirstAndLastName() : $user->getUsername(),
                (new \DateTime())->format('d/m/Y')
            )
        );
        $payment->setClientId($user->getId());
        $payment->setClientEmail($user->getEmail());

        $storage->update($payment);

        /** @var TokenInterface $captureToken */
        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $payment,
            'atypikhouse_payment_user_done'
        );

        return $this->redirect($captureToken->getTargetUrl());
    }

    /**
     * Translate the token from paypal and redirect to right action
     *
     * @param Request $request Get the targeted request
     *
     * @return RedirectResponse
     */
    public function doneAction(Request $request)
    {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);
        $gateway = $this->get('payum')->getGateway($token->getGatewayName());
        $gateway->execute($status = new GetHumanStatus($token));
        /** @var PaymentInfos $payment */
        $payment = $status->getFirstModel();
        $reservation = $payment->getReservation();

        $em = $this->getDoctrine()->getManager();

        if (PaymentStateEnum::COMPLETED === strtolower($payment->getDetails()['PAYMENTINFO_0_PAYMENTSTATUS'])) {
            $reservation->setState(ReservationStateEnum::DONE);
            $em->flush();
            return $this->redirectToRoute(
                'atypikhouse_reservation_step_four',
                [
                'id' => $reservation->getId(),
                'slug' => $reservation->getHousing()->getSlug(),
                ]
            );
        }

        return $this->redirectToRoute(
            'atypikhouse_reservation_step_error',
            [
            'id' => $reservation->getId(),
            'slug' => $reservation->getHousing()->getSlug(),
            'status' => $status->getValue(),
            ]
        );
    }

    /**
     * Get all houses of the proprietary
     *
     * @return Response
     */
    public function allProprietaryPaymentAction()
    {
        $em = $this->getDoctrine()->getManager();
        $payments = $em->getRepository(PaymentInfos::class)->getProprietaryPayment($this->getUser()->getId());

        return $this->render(
            'PaymentBundle:admin:list.html.twig',
            [
            'payments' => $payments,
            'menu_level' => 'payment_prorietary'
            ]
        );
    }
}