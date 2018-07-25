<?php

namespace HousingBundle\Controller;

use HousingBundle\Entity\Housing;
use HousingBundle\Enum\HousingStateEnum;
use HousingBundle\Form\HousingDetailFormType;
use HousingBundle\Form\HousingDocumentsFormType;
use HousingBundle\Form\HousingFirstFormType;
use HousingBundle\Form\HousingTeaserFormType;
use HousingBundle\Form\HousingUnavailabilityFormType;
use HousingBundle\Service\HousingManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Housing Admin controller.
 *
 * In this controller admin can manage all houses
 *
 * PHP version 7.1
 *
 * @category  Controller
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class HousingAdminController extends Controller
{
    /**
     * Lists all housing entities.
     *
     * @return Response
     */
    public function allAction()
    {
        $housings = $this->get('ah.housing_manager')->getAllHousingEntity();
        return $this->render(
            'HousingBundle:housing:list.html.twig',
            [
                'housings' => $housings,
                'menu_level' => 'housings_admin'
            ]
        );
    }

    /**
     * Lists all housing waiting for a validation.
     *
     * @return Response
     */
    public function allWaitingAction()
    {
        $em = $this->getDoctrine()->getManager();
        $housings = $em->getRepository(Housing::class)->findBy(['state' => HousingStateEnum::VALIDATION_ASK]);
        return $this->render(
            'HousingBundle:housing:list-waiting.html.twig',
            [
                'housings' => $housings,
            ]
        );
    }

    /**
     * Validate housing publication
     *
     * @param string $slug Get the targeted housing slug
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateAction(string $slug)
    {
        try {
            $housing = $this->get('ah.housing_manager')->getHousingEntity($slug);
            $em = $this->getDoctrine()->getManager();
            $housing->setState(HousingStateEnum::VALIDATED);
            $housing->setVisible(true);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s housing has been validated', $housing->getTitle()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atyipikhouse_admin_housing_waiting');
    }

    /**
     * Refuse housing publication
     *
     * @param string $slug Get the targeted housing slug
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refuseAction(string $slug)
    {
        try {
            $housing = $this->get('ah.housing_manager')->getHousingEntity($slug);
            $em = $this->getDoctrine()->getManager();
            $housing->setState(HousingStateEnum::REFUSED);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s housing has been refused', $housing->getTitle()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atyipikhouse_admin_housing_waiting');
    }

    /**
     * Refuse housing publication
     *
     * @param string $slug Get the targeted housing slug
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(string $slug)
    {
        try {
            $housing = $this->get('ah.housing_manager')->getHousingEntity($slug);
            $em = $this->getDoctrine()->getManager();
            $housing->setDeletedAt(new \DateTime());
            $housing->setState(HousingStateEnum::CANCELED);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s housing has been archived', $housing->getTitle()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atyipikhouse_admin_housing_index');
    }


    /**
     * Undelete housing publication
     *
     * @param string $slug Get the targeted housing slug
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function undeleteAction(string $slug)
    {
        try {
            $housing = $this->get('ah.housing_manager')->getHousingEntity($slug);
            $em = $this->getDoctrine()->getManager();
            $housing->setDeletedAt(null);
            $housing->setState(HousingStateEnum::CREATED);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s housing has been unarchived', $housing->getTitle()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atyipikhouse_admin_housing_index');
    }
}
