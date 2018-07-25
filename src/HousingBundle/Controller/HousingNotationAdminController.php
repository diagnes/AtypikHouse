<?php

namespace HousingBundle\Controller;

use HousingBundle\Entity\HousingType;
use HousingBundle\Form\HousingTypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Housing Notation Admin controller.
 *
 * In this controller we can list, add, edit and delete
 * Housing Notation information
 *
 * PHP version 7.1
 *
 * @category  Controller
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class HousingNotationAdminController extends Controller
{
    /**
     * Lists all housing Type entities
     *
     * @return Response
     */
    public function allAction()
    {
        $housingNotations = $this->get('ah.housing_notation_manager')->getAllHousingNotationEntity();
        return $this->render(
            'HousingBundle:housing-notation:list.html.twig',
            [
            'housingNotations' => $housingNotations,
            ]
        );
    }

    /**
     * Creates a new housing Type information.
     *
     * @param Request $request Get the request for form information
     *
     * @return RedirectResponse|Response
     *
     * @internal Throw FlashBag for user
     *
     * @throws \LogicException
     */
    public function newAction(Request $request)
    {
        $housingNotation = new HousingType();
        $form = $this->createForm(HousingTypeType::class, $housingNotation, []);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($housingNotation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The '.$housingNotation->getName().' category has been created');
            return $this->redirectToRoute('atyipikhouse_admin_housing_notation_edit', ['slug' => $housingNotation->getSlug()]);
        }

        return $this->render(
            'HousingBundle:housing-notation:housing-notation-form.html.twig',
            [
            'housingNotation' => $housingNotation,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing housing entity.
     *
     * @param Request $request Get the request for form information
     * @param string  $slug    Get slug for retrieve right HousingType
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @internal Throw FlashBag for user
     *
     * @throws \ErrorException
     * @throws \LogicException
     */
    public function editAction(Request $request, $slug)
    {
        $housingNotationManager = $this->get('ah.housing_notation_manager');
        $housingNotation = $housingNotationManager->getHousingTypeEntity($slug);
        $form = $this->createForm(HousingTypeType::class, $housingNotation, []);
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($housingNotation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The '.$housingNotation->getName().' category has been modified');
        }

        return $this->render(
            'HousingBundle:housing-notation:housing-notation-form.html.twig',
            [
            'housingNotation' => $housingNotation,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Delete housing type
     *
     * @param string $slug Get slug for retrieve right HousingType
     *
     * @return RedirectResponse
     *
     * @internal Throw FlashBag for user
     *
     * @throws \LogicException
     */
    public function deleteAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $housingNotationManager = $this->get('ah.housing_notation_manager');
            $housingNotation = $housingNotationManager->getHousingTypeEntity($slug);
            $housingNotationManager->isDeletable($housingNotation);
            $housingNotation->setDeletedAt(new \DateTime());
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s category has been archived', $housingNotation->getName()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atyipikhouse_admin_housing_notation_index');
    }

    /**
     * Unelete housing type
     *
     * @param string $slug Get slug for retrieve right HousingType
     *
     * @return RedirectResponse
     *
     * @internal Throw FlashBag for user
     *
     * @throws \LogicException
     */
    public function undeleteAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $housingNotationManager = $this->get('ah.housing_notation_manager');
            $housingNotation = $housingNotationManager->getHousingTypeEntity($slug);
            $housingNotationManager->isDeletable($housingNotation);
            $housingNotation->setDeletedAt(null);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s category has been reactivate', $housingNotation->getName()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atyipikhouse_admin_housing_notation_index');
    }
}
