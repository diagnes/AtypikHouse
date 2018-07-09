<?php

/*
 * This file is part of the FOSUserBundle package modify by Nefast
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Controller;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
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
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if (!\is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render(
            'UserBundle::profile.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * Edit the user.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \LogicException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function editAction(Request $request): Response
    {
        $user = $this->getUser();
        if (!\is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

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
}
