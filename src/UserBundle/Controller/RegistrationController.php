<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UserBundle\Form\RegistrationProType;

/**
 * Controller managing the registration.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends Controller
{
    /**
     * @param Request $request Get the request
     *
     * @return null|RedirectResponse|Response
     *
     * @throws \InvalidArgumentException
     */
    public function registerAction(Request $request)
    {
        return $this->returnConnectionResponse($request, 'simple');
    }

    /**
     * @param Request $request Get the request
     *
     * @return null|RedirectResponse|Response
     */
    public function registerProAction(Request $request)
    {
        return $this->returnConnectionResponse($request, 'pro');
    }

    /**
     * Tell the user to check their email provider.
     *
     * @return RedirectResponse|Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \InvalidArgumentException
     */
    public function checkEmailAction()
    {
        $email = $this->get('session')->get('fos_user_send_confirmation_email/email');

        if (empty($email)) {
            return new RedirectResponse($this->get('router')->generate('fos_user_registration_register'));
        }

        $this->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->render(
            '@FOSUser/Registration/check_email.html.twig',
            [
            'user' => $user,
            ]
        );
    }

    /**
     * Receive the confirmation token from user email provider, login the user.
     *
     * @param Request $request Get the request
     * @param string  $token   Get the token reset password
     *
     * @return Response
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function confirmAction(Request $request, $token)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /**  @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('fos_user_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    /**
     * Tell the user his account is now confirmed.
     *
     * @return RedirectResponse
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException
     */
    public function confirmedAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        if ($authChecker->isGranted('ROLE_PROPRIETARY')) {
            return new RedirectResponse($router->generate('admin_homepage'), 307);
        }
        return new RedirectResponse($router->generate('atypikhouse_home'), 307);
    }

    /**
     * Return connection system for both system Register and register hasPro
     *
     * @param  Request $request Get the request
     * @param  string  $type    Get the type of registration
     *
     * @SuppressWarnings(PHPMD)
     *
     * @return null|RedirectResponse|Response
     * @throws \InvalidArgumentException
     */
    private function returnConnectionResponse(Request $request, string $type = 'simple')
    {
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        if ($authChecker->isGranted('ROLE_PROPRIETARY')) {
            return new RedirectResponse($router->generate('admin_homepage'), 307);
        }
        if ($authChecker->isGranted('ROLE_USER') && 'simple' === $type) {
            return new RedirectResponse($router->generate('atypikhouse_home'), 307);
        }
        /**
         * @var $formFactory FactoryInterface
         */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /**
         * @var $dispatcher EventDispatcherInterface
         */
        $dispatcher = $this->get('event_dispatcher');
        /**
         * @var UserManager $userManager
         */
        $userManager = $this->get('fos_user.user_manager');

        $user = $this->getUser() ?: $userManager->createUser();

        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return new JsonResponse($event->getResponse());
        }

        $form = ('simple' === $type) ? $formFactory->createForm() : $this->createForm(
            RegistrationProType::class,
            null,
            [
            'action' => $this->generateUrl('fos_user_registration_register_pro')
            ]
        );

        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                if ('simple' === $type) {
                    $this->get('ah.notification_manager')->userRegisterNotification();
                } else {
                    $this->get('ah.notification_manager')->userRegisterProNotification();
                }
                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }
        return $this->render(
            $this->getRegisterView($type),
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Get the right view for register action
     *
     * @param string $type Get the view type of register
     *
     * @return string
     */
    private function getRegisterView(string $type)
    {
        $views = [
            'simple' => 'UserBundle::register.html.twig',
            'pro' => 'UserBundle::register-pro.html.twig'
        ];

        return $views[$type];
    }
}
