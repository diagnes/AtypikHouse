<?php

namespace HousingBundle\Controller;

use HousingBundle\Entity\Housing;
use HousingBundle\Enum\HousingStateEnum;
use HousingBundle\Form\HousingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ToolsBundle\Service\DataResponseAdapter;

/**
 * Housing controller.
 */
class HousingController extends Controller
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
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository('AtypikHouseBundle:Reservation')->findAll();

        return $this->render(
            'reservation/index.html.twig',
            [
            'reservations' => $reservations,
            ]
        );
    }

    /**
     * Finds and displays a housing entity.
     *
     * @param int $id Get the housing id targeted
     *
     * @return JsonResponse
     */
    public function showAction(int $id)
    {
        try {
            $housing = $this->get('ah.housing_manager')->getHousingEntity($id);
            $dataResonseManager = $this->get('tools.data_response_manager');
            $data = [
                'housing' => new DataResponseAdapter($housing, Housing::class),
            ];

            return new JsonResponse($dataResonseManager->createAdaptedResponseData($data), 200);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}
