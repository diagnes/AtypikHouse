<?php

namespace AtypikHouseBundle\Controller;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use AtypikHouseBundle\Form\ReservationType;
use AtypikHouseBundle\Form\SearchHouseFormType;
use HousingBundle\Entity\Housing;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ToolsBundle\Service\DataResponseAdapter;

/**
 * Reservation controller.
 */
class IndexController extends Controller
{
    /**
     * @param Request $request Get the targeted Request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $housingRepo = $em->getRepository(Housing::class);
        $form = $this->createForm(SearchHouseFormType::class, null);
        $housings = $housingRepo->findBy([], ['createdAt' => 'DESC'], 3);
        $topCity = $housingRepo->getTopCityTravel();
        $housingsTotal = $housingRepo->getTotalHousing();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $dest = $data['destination'];
            $resident = $data['resident'];
            return $this->redirect(
                $this->generateUrl('atypikhouse_housing_index').sprintf('?destination=%s&resident=%s', $dest, $resident)
            );
        }

        return $this->render(
            'AtypikHouseBundle:Default:index.html.twig',
            [
            'form' => $form->createView(),
            'housings' => $housings,
            'topCity' => $topCity,
            'housingsTotal' => $housingsTotal
            ]
        );
    }
}
