<?php

namespace HousingBundle\Controller;

use Doctrine\ORM\EntityManager;
use HousingBundle\Entity\HousingNotationType;
use HousingBundle\Entity\HousingType;
use HousingBundle\Form\HousingNotationTypeType;
use HousingBundle\Form\HousingTypeType;
use Http\Discovery\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Housing Notation Type controller.
 *
 * In this controller we can list, add, edit and delete
 * Housing Notation Type information
 *
 * PHP version 7.1
 *
 * @category  Controller
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class HousingNotationTypeController extends Controller
{
    /**
     * Lists all housing notation type entities
     *
     * @return Response
     */
    public function allAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('deleted');
        $housingNotationTypes = $em->getRepository(HousingNotationType::class)->findAll();
        return $this->render(
            'HousingBundle:housing-notation-type:list.html.twig',
            [
            'housingNotationTypes' => $housingNotationTypes,
            ]
        );
    }

    /**
     * Creates a new housing notation type information.
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
        $housingNotationType = new HousingNotationType();
        $form = $this->createForm(HousingNotationTypeType::class, $housingNotationType, []);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($housingNotationType);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The '.$housingNotationType->getName().' reviews type has been created');
            return $this->redirectToRoute('atyipikhouse_admin_housing_notation_type_edit', ['id' => $housingNotationType->getId()]);
        }

        return $this->render(
            'HousingBundle:housing-notation-type:form.html.twig',
            [
            'housingNotationType' => $housingNotationType,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing housing entity.
     *
     * @param Request $request Get the request for form information
     * @param string  $id      Get id for retrieve right HousingType
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @internal Throw FlashBag for user
     *
     * @throws \ErrorException
     * @throws \LogicException
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('deleted');
        $housingNotationType = $em->getRepository(HousingNotationType::class)->findOneBy(['id' => $id]);
        if (null === $housingNotationType) {
            throw new AccessDeniedException('la review type id:'.$id.' not exist');
        }
        $form = $this->createForm(HousingNotationTypeType::class, $housingNotationType, []);
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($housingNotationType);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The '.$housingNotationType->getId().' reviews type has been modified');
            return $this->redirectToRoute('atyipikhouse_admin_housing_notation_type_edit', ['id' => $housingNotationType->getId()]);
        }

        return $this->render(
            'HousingBundle:housing-notation-type:form.html.twig',
            [
            'housingNotationType' => $housingNotationType,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Delete housing type
     *
     * @param string $id Get id for retrieve right HousingType
     *
     * @return RedirectResponse
     *
     * @internal Throw FlashBag for user
     *
     * @throws \LogicException
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('deleted');
        try {
            $housingNotationType = $em->getRepository(HousingNotationType::class)->findOneBy(['id' => $id]);
            $housingNotationType->setDeletedAt(new \DateTime());
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s category has been archived', $housingNotationType->getName()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atyipikhouse_admin_housing_notation_type_index');
    }

    /**
     * Unelete housing type
     *
     * @param string $id Get id for retrieve right HousingType
     *
     * @return RedirectResponse
     *
     * @internal Throw FlashBag for user
     *
     * @throws \LogicException
     */
    public function undeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('deleted');
        try {
            $housingNotationType = $em->getRepository(HousingNotationType::class)->findOneBy(['id' => $id]);
            $housingNotationType->setDeletedAt(null);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The %s category has been reactivate', $housingNotationType->getName()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atyipikhouse_admin_housing_notation_type_index');
    }
}
