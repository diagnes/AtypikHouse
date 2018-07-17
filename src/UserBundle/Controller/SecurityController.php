<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class SecurityController extends BaseController
{
    /**
     *
     * @param Request $request Get the request symfony parameters
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function loginAction(Request $request)
    {
        /**
         * @var $session \Symfony\Component\HttpFoundation\Session\Session
         */
        $session = $request->getSession();
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        if ($authChecker->isGranted('ROLE_USER')) {
            return new RedirectResponse($router->generate('atypikhouse_home'), 307);
        }
        if ($authChecker->isGranted('ROLE_PROPRIETARY')) {
            return new RedirectResponse($router->generate('admin_homepage'), 307);
        }

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        return $this->render(
            'UserBundle::login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
            ]
        );
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data Get data
     *
     * @return Response
     */
    protected function renderLogin(array $data): Response
    {
        return $this->render('@FOSUser/Security/login.html.twig', $data);
    }

    /**
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function checkAction(): void
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function logoutAction(): void
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
