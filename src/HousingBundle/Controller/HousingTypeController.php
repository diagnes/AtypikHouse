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
 * Housing Type controller.
 *
 * In this controller we can list, add, edit and delete
 * Housing Type information
 *
 * PHP version 7.1
 *
 * @category  Controller
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class HousingTypeController extends Controller
{
    /**
     * Lists all housing Type entities
     *
     * @return Response
     */
    public function allAction()
    {
        $housingTypes = $this->get('ah.housing_type_manager')->getAllHousingTypeEntity();
        return $this->render(
            'HousingBundle:housing-type:list.html.twig',
            [
            'housingTypes' => $housingTypes,
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
        $housingType = new HousingType();
        $form = $this->createForm(HousingTypeType::class, $housingType, []);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($housingType);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The '.$housingType->getName().' category has been created');
            return $this->redirectToRoute('atyipikhouse_admin_housing_type_edit', ['slug' => $housingType->getSlug()]);
        }

        return $this->render(
            'HousingBundle:housing-type:housing-type-form.html.twig',
            [
            'housingType' => $housingType,
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
        $housingTypeManager = $this->get('ah.housing_type_manager');
        $housingType = $housingTypeManager->getHousingTypeEntity($slug);
        $form = $this->createForm(HousingTypeType::class, $housingType, []);
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($housingType);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The '.$housingType->getName().' category has been modified');
        }

        return $this->render(
            'HousingBundle:housing-type:housing-type-form.html.twig',
            [
            'housingType' => $housingType,
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
            $housingTypeManager = $this->get('ah.housing_type_manager');
            $housingType = $housingTypeManager->getHousingTypeEntity($slug);
            $housingTypeManager->isDeletable($housingType);
            $housingType->setDeletedAt(new \DateTime());
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s category has been archived', $housingType->getName()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atyipikhouse_admin_housing_type_index');
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
            $housingTypeManager = $this->get('ah.housing_type_manager');
            $housingType = $housingTypeManager->getHousingTypeEntity($slug);
            $housingTypeManager->isDeletable($housingType);
            $housingType->setDeletedAt(null);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s category has been reactivate', $housingType->getName()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atyipikhouse_admin_housing_type_index');
    }
}
