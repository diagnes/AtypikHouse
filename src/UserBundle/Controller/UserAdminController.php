<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Doctrine\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;
use UserBundle\Form\UserInfoFormType;
use UserBundle\Form\UserPersonalFormAdminType;
use UserBundle\Form\UserProfessionalFormAdminType;
use UserBundle\Form\UserSecurityFormType;

/**
 * Controller managing the users.
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserAdminController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @return Response
     *
     * @throws \LogicException
     */
    public function allAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();
        return $this->render(
            'UserBundle:admin:list.html.twig',
            [
            'users' => $users
            ]
        );
    }

    /**
     * Create a new user entity.
     *
     * @param Request $request Get the request for session
     *
     * @return RedirectResponse|Response
     *
     * @throws \LogicException
     */
    public function newAction(Request $request)
    {
        /** @var UserManager $userManager */
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setEnabled(true);

        return $this->returnFormView($request, $user, UserInfoFormType::class);
    }

    /**
     * Edit general infos user entity.
     *
     * @param Request $request Get the request for session
     * @param User    $user    Get the targeted User
     *
     * @return RedirectResponse|Response
     *
     * @throws \LogicException
     *
     * @throws \LogicException
     */
    public function editAction(Request $request, User $user)
    {
        return $this->returnFormView($request, $user, UserInfoFormType::class);
    }

    /**
     * Edit a personal info user entity.
     *
     * @param Request $request Get the request for session
     * @param User    $user    Get the targeted User
     *
     * @return RedirectResponse|Response
     *
     * @throws \LogicException
     */
    public function editPersoAction(Request $request, User $user)
    {
        return $this->returnFormView($request, $user, UserPersonalFormAdminType::class);
    }

    /**
     * Edit a professional info user entity.
     *
     * @param Request $request Get the request for session
     * @param User    $user    Get the targeted User
     *
     * @return RedirectResponse|Response
     *
     * @throws \LogicException
     */
    public function editProAction(Request $request, User $user)
    {
        return $this->returnFormView($request, $user, UserProfessionalFormAdminType::class);
    }

    /**
     * Deletes a user entity.
     *
     * @param Request $request Get the request for session
     * @param User    $user    Get the targeted User
     *
     * @return RedirectResponse
     *
     * @throws \LogicException
     */
    public function deleteAction(Request $request, User $user)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $user->setEnabled(false);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s user has been disabled', $user->getUsername()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atypikhouse_user_admin_all');
    }

    /**
     * Undeletes a user entity.
     *
     * @param User $user Get the targeted User
     *
     * @return RedirectResponse
     */
    public function undeleteAction(User $user)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $user->setEnabled(true);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s user has been enabled', $user->getUsername()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atypikhouse_user_admin_all');
    }

    /**
     * Return view and action depending on action
     *
     * @param Request $request   Get the session request
     * @param User    $user      Get the targeted user
     * @param string  $formClass Get the action status for saving user
     *
     * @return Response
     * @throws \LogicException
     */
    private function returnFormView(Request $request, User $user, $formClass): Response
    {
        /** @var UserManager $userManager */
        $userManager = $this->get('fos_user.user_manager');
        $form = $this->createForm($formClass, $user, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($user);
            return $this->redirectToRoute($this->getRedirectForFormType($formClass), ['id' => $user->getId()]);
        }
        
        return $this->render(
            $this->getViewForFormType($formClass),
            [
            'form' => $form->createView(),
            'user' => $user,
            ]
        );
    }

    /**
     * Return the right view dependin on the formClass
     *
     * @param string $formClass Get the form class targeted
     *
     * @return string
     */
    private function getViewForFormType(string $formClass): string
    {
        $views = [
            UserInfoFormType::class => 'UserBundle:admin/form:user-details.html.twig',
            UserPersonalFormAdminType::class => 'UserBundle:admin/form:user-perso.html.twig',
            UserProfessionalFormAdminType::class => 'UserBundle:admin/form:user-pro.html.twig',
        ];
        return $views[$formClass];
    }

    /**
     * Return the right view dependin on the formClass
     *
     * @param string $formClass Get the form class targeted
     *
     * @return string
     */
    private function getRedirectForFormType(string $formClass): string
    {
        $views = [
            UserInfoFormType::class => 'atypikhouse_user_admin_edit',
            UserPersonalFormAdminType::class => 'atypikhouse_user_admin_edit_perso',
            UserProfessionalFormAdminType::class => 'atypikhouse_user_admin_edit_pro',
        ];
        return $views[$formClass];
    }
}
