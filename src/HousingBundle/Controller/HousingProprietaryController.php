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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Housing Proprietary controller.
 *
 * In this controller Proprietary can manage all his houses
 *
 * PHP version 7.1
 *
 * @category  Controller
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 *
 * @Security("has_role('ROLE_PROPRIETARY')")
 */
class HousingProprietaryController extends Controller
{
    /**
     * Creates a new housing entity.
     *
     * @param Request $request Get the request off actuall action
     *
     * @return Response
     *
     * @throws \LogicException
     */
    public function newAction(Request $request)
    {
        $housingManager = $this->get('ah.housing_manager');
        $em = $this->getDoctrine()->getManager();
        $housing = $housingManager->createNewHousing();
        $form = $this->createForm(HousingFirstFormType::class, $housing, []);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $housingManager->isValidHousing($housing);
            $em->persist($housing);
            $em->flush();
            $this->redirectToRoute('atyipikhouse_admin_housing_edit', ['slug' => $housing->getSlug()]);
        }

        return $this->render(
            'HousingBundle:housing:housing-form-infos.html.twig',
            [
            'form' => $form->createView(),
            'housing' => $housing,
            ]
        );
    }

    /**
     * Displays a form to edit an existing housing entity.
     *
     * @param Request $request Get the request off actuall action
     * @param string  $slug    Get the targeted Housing slug
     *
     * @return Response
     *
     * @throws \ErrorException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function editAction(Request $request, $slug)
    {
        $housingManager = $this->get('ah.housing_manager');
        $housing = $housingManager->getHousingEntity($slug);
        return $this->formResponse($request, $housing, $housingManager, HousingFirstFormType::class);
    }

    /**
     * Displays a form to edit details of an existing housing entity.
     *
     * @param Request $request Get the request off actuall action
     * @param string  $slug    Get the targeted Housing slug
     *
     * @return Response
     *
     * @throws \ErrorException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function editDetailsAction(Request $request, $slug)
    {
        $housingManager = $this->get('ah.housing_manager');
        $housing = $housingManager->getHousingEntity($slug);
        return $this->formResponse($request, $housing, $housingManager, HousingDetailFormType::class);
    }

    /**
     * Displays a form to edit teser of an existing housing entity.
     *
     * @param Request $request Get the request off actuall action
     * @param string  $slug    Get the targeted Housing slug
     *
     * @return Response
     *
     * @throws \ErrorException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function editTeaserAction(Request $request, $slug)
    {
        $housingManager = $this->get('ah.housing_manager');
        $housing = $housingManager->getHousingEntity($slug);
        return $this->formResponse($request, $housing, $housingManager, HousingTeaserFormType::class);
    }

    /**
     * Displays a form to edit unavailability of an existing housing entity.
     *
     * @param Request $request Get the request off actuall action
     * @param string  $slug    Get the targeted Housing slug
     *
     * @return Response
     *
     * @throws \ErrorException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function editUnavailabilityAction(Request $request, $slug)
    {
        $housingManager = $this->get('ah.housing_manager');
        $housing = $housingManager->getHousingEntity($slug);
        return $this->formResponse($request, $housing, $housingManager, HousingUnavailabilityFormType::class);
    }

    /**
     * Displays a form to edit document of an existing housing entity.
     *
     * @param Request $request Get the request off actuall action
     * @param string  $slug    Get the targeted Housing slug
     *
     * @return Response
     *
     * @throws \ErrorException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    public function editDocumentAction(Request $request, $slug)
    {
        $housingManager = $this->get('ah.housing_manager');
        $housing = $housingManager->getHousingEntity($slug);
        return $this->formResponse($request, $housing, $housingManager, HousingDocumentsFormType::class);
    }

    /**
     * Ask for a validation by an adminfor housing publication
     *
     * @param string $slug Get the targeted housing slug
     *
     * @return RedirectResponse
     */
    public function validationAskAction(string $slug)
    {
        try {
            $housing = $this->get('ah.housing_manager')->getHousingEntity($slug);
            $em = $this->getDoctrine()->getManager();
            $housing->setState(HousingStateEnum::VALIDATION_ASK);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The validation ask for %s housing has been sent', $housing->getTitle()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atyipikhouse_admin_housing_index');
    }


    /**
     * Refactoring of Edit and add action
     *
     * @param Request        $request        Get the request off actuall action
     * @param Housing        $housing        Get the targeted Housing
     * @param HousingManager $housingManager Get the Housing manager created before
     * @param string         $formClass      Get the right Form to submit or load
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     */
    private function formResponse(Request $request, Housing $housing, HousingManager $housingManager, string $formClass): \Symfony\Component\HttpFoundation\Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm($formClass, $housing, []);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $housingManager->isValidHousing($housing);
            $housingManager->editHousingValidation($housing);
            $em->flush();
            return $this->redirectToRoute($this->getRedirectForFormType($formClass), ['slug' => $housing->getSlug()]);
        }

        $view = $this->getViewForFormType($formClass);

        return $this->render(
            $view,
            [
            'form' => $form->createView(),
            'housing' => $housing,
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
            HousingFirstFormType::class => 'HousingBundle:housing:housing-form-infos.html.twig',
            HousingDetailFormType::class => 'HousingBundle:housing:housing-form-details.html.twig',
            HousingTeaserFormType::class => 'HousingBundle:housing:housing-form-teaser.html.twig',
            HousingUnavailabilityFormType::class => 'HousingBundle:housing:housing-form-unavailability.html.twig',
            HousingDocumentsFormType::class => 'HousingBundle:housing:housing-form-document.html.twig',
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
            HousingFirstFormType::class => 'atyipikhouse_admin_housing_edit',
            HousingDetailFormType::class => 'atyipikhouse_admin_housing_edit_detail',
            HousingTeaserFormType::class => 'atyipikhouse_admin_housing_edit_teaser',
            HousingUnavailabilityFormType::class => 'atyipikhouse_admin_housing_edit_unavailability',
            HousingDocumentsFormType::class => 'atyipikhouse_admin_housing_edit_document',
        ];
        return $views[$formClass];
    }
}
