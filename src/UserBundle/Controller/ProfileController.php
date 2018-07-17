<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UserBundle\Entity\User;
use UserBundle\Form\UserProfileType;

/**
 * Controller managing the user profile.
 *
 * @author Christophe Coevoet <stof@notk.org>
 *
 * @Security("has_role('ROLE_USER')")
 */
class ProfileController extends Controller
{
    /**
     * Show the user.
     *
     * @return Response
     */
    public function showAction(): Response
    {
        return $this->render('UserBundle::profile.html.twig');
    }

    /**
     * Edit the user.
     *
     * @param Request $request Get the request for session
     *
     * @return Response
     *
     * @throws \LogicException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function editAction(Request $request): Response
    {
        $user = $this->getUser();
        /**
         * @var $dispatcher EventDispatcherInterface
         */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render(
            'UserBundle::profile-edit.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Show the user reservations.
     *
     * @return Response
     */
    public function showReservationAction(): Response
    {
        return $this->render('UserBundle:profile:reservations.html.twig');
    }

    /**
     * Show the user wishlist.
     *
     * @return Response
     */
    public function showWishListAction(): Response
    {
        return $this->render('UserBundle:profile:wishlist.html.twig');
    }

    /**
     * Show the user Notation.
     *
     * @return Response
     */
    public function showNotationListAction(): Response
    {
        return $this->render('UserBundle:profile:notation.html.twig');
    }

    /**
     * Show the user wishlist.
     *
     * @return Response
     */
    public function showHousingListAction(): Response
    {
        return $this->render('UserBundle:profile:housing.html.twig');
    }
}
