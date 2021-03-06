<?php

namespace HousingBundle\Controller;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Form\SearchHouseFormType;
use HousingBundle\Entity\Housing;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Housing controller.
 *
 * In this controller user can see all available houses
 *
 * PHP version 7.1
 *
 * @category  Controller
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 *
 * @Security("has_role('ROLE_USER')")
 */
class HousingController extends Controller
{
    /**
     * Lists all housing entities.
     *
     * @param Request $request Get the request
     *
     * @return Response
     */
    public function allAction(Request $request)
    {
        $queryInfos = $request->query->all();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SearchHouseFormType::class, null);
        $form->handleRequest($request);
        if ($queryInfos) {
            $page = $queryInfos['page'] ?? 1;
            $housingIds = $em->getRepository(Housing::class)->getHousingByQuery($queryInfos);
            $housings = $this->get('ah.housing_manager')->getAllHousingFrontEntity($housingIds, $page);
        } else {
            $housings = $this->get('ah.housing_manager')->getAllHousingFrontEntity();
        }

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $housingIds = $em->getRepository(Housing::class)->getHousingByQuery($data);
            $page = $data['page'] ?? 1;
            $housings = $this->get('ah.housing_manager')->getAllHousingFrontEntity($housingIds, $page);
        }

        return $this->render(
            'HousingBundle:front-housing:list.html.twig',
            [
            'housings' => $housings,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Lists all reservation entities.
     *
     * @Security("has_role('ROLE_PROPRIETARY')")
     *
     * @return Response
     */
    public function listProprietaryHousingAction()
    {
        $housingManager = $this->get('ah.housing_manager');
        $housings = $housingManager->getAllHousingProprietaryEntity();
        return $this->render(
            'HousingBundle:housing:list.html.twig',
            [
            'housings' => $housings,
            'menu_level' => 'housing_prorietary'
            ]
        );
    }

    /**
     * Finds and displays a housing entity for users.
     *
     * @param string $slug Get the housing id targeted
     *
     * @return Response
     */
    public function showAction(string $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $housing = $this->get('ah.housing_manager')->getHousingFrontEntity($slug);
        $reservations = $em->getRepository(Reservation::class)->getHousingReviews($slug);

        return $this->render(
            'HousingBundle:front-housing:show.html.twig',
            [
            'housing' => $housing,
            'reservations' => $reservations
            ]
        );
    }
}
