<?php

namespace HousingBundle\Controller;

use HousingBundle\Entity\Housing;
use HousingBundle\Enum\HousingStateEnum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Housing controller.
 *
 * @Security("has_role('ROLE_PROPRIETARY')")
 */
class HousingAdminController extends Controller
{
    /**
     * Validate housing publication
     */
    public function validateAction($housingId)
    {
        try {
            $housing = $this->get('ah.housing_manager')->getHousingEntity($housingId);
            $em = $this->getDoctrine()->getManager();
            $housing->setState(HousingStateEnum::VALIDATED);
            $housing->setVisible(true);
            $em->flush();
            return new JsonResponse('Housing has been validate', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    /**
     * Refuse housing publication
     */
    public function refuseAction($housingId)
    {
        try {
            $housing = $this->get('ah.housing_manager')->getHousingEntity($housingId);
            $em = $this->getDoctrine()->getManager();
            $housing->setState(HousingStateEnum::REFUSED);
            $em->flush();
            return new JsonResponse('Housing has been refused', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    /**
     * Refuse housing publication
     */
    public function deleteAction($housingId)
    {
        try {
            $housing = $this->get('ah.housing_manager')->getHousingEntity($housingId);
            $em = $this->getDoctrine()->getManager();
            $housing->setDeletedAt(new \DateTime());
            $housing->setState(HousingStateEnum::CANCELED);
            $em->flush();
            return new JsonResponse('Housing has been deleted', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }


    /**
     * Refuse housing publication
     */
    public function undeleteAction($housingId)
    {
        try {
            $housing = $this->get('ah.housing_manager')->getHousingEntity($housingId);
            $em = $this->getDoctrine()->getManager();
            $housing->setDeletedAt(null);
            $housing->setState(HousingStateEnum::CREATED);
            $em->flush();
            return new JsonResponse('Housing has been undeleted', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}
