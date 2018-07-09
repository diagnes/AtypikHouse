<?php

namespace HousingBundle\Controller;

use HousingBundle\Entity\Housing;
use HousingBundle\Enum\HousingStateEnum;
use HousingBundle\Form\HousingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ToolsBundle\Service\DataResponseAdapter;

/**
 * Housing controller.
 *
 * @Security("has_role('ROLE_PROPRIETARY')")
 */
class HousingTypeController extends Controller
{
    /**
     * Lists all housing entities.
     *
     */
    public function allAction()
    {
        $dataResonseManager = $this->get('tools.data_response_manager');

        $housings = $this->get('ah.housing_type_manager')->getAllHousingEntity();
        $data = [
            'housings' => new DataResponseAdapter($housings, Housing::class),
        ];

        return new JsonResponse($dataResonseManager->createAdaptedResponseData($data), 200);
    }

    /**
     * Creates a new housing entity.
     *
     */
    public function newAction(Request $request)
    {
        $housing = new HousingType();
        $form = $this->createForm(HousingType::class, $housing, []);
        $em = $this->getDoctrine()->getManager();
        $dataResonseManager = $this->get('tools.data_response_manager');
        $housingManager = $this->get('ah.housing_manager');

        $form->handleRequest($request);
        try {
            switch (true) {
                case $request->isMethod('GET'):
                    return new JsonResponse(
                        $dataResonseManager->createAdaptedResponseData(
                            [
                                'form' => new DataResponseAdapter($dataResonseManager->createCustomForm($form->createView()), FormView::class),
                            ]
                        ),
                        200
                    );
                    break;
                case $request->isMethod('POST'):
                    if ($form->isSubmitted()) {
                        $housingManager->isValidHousing($housing);
                        $housing->setState(HousingStateEnum::CREATED);
                        $em->persist($housing);
                        $em->flush();
                    }
                    break;
            }
            return new JsonResponse('Well done, your housing has been created', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
    }

    /**
     * Finds and displays a housing entity.
     *
     */
    public function showAction(Housing $housing)
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

    /**
     * Displays a form to edit an existing housing entity.
     *
     */
    public function editAction(Request $request, $housingId)
    {
        $dataResonseManager = $this->get('tools.data_response_manager');
        $housingManager = $this->get('ah.housing_manager');

        $housing = $housingManager->getHousingEntity($housingId);
        $form = $this->createForm(HousingType::class, $housing, []);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        try {
            switch (true) {
                case $request->isMethod('GET'):
                    return new JsonResponse(
                        $dataResonseManager->createAdaptedResponseData(
                            [
                                'form' => new DataResponseAdapter($dataResonseManager->createCustomForm($form->createView()), FormView::class),
                            ]
                        ),
                        200
                    );
                    break;
                case $request->isMethod('POST'):
                    if ($form->isSubmitted()) {
                        $housingManager->isValidHousing($housing);
                        $em->flush();
                    }
                    break;
            }
            return new JsonResponse('Well done, your housing has been created', 201);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
    }
}
