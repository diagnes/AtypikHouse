<?php

namespace HousingBundle\Controller;

use HousingBundle\Entity\Housing;
use HousingBundle\Enum\HousingStateEnum;
use HousingBundle\Form\HousingTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ToolsBundle\Service\DataResponseAdapter;

/**
 * Housing controller.
 */
class HousingApiController extends Controller
{
    /**
     * Lists all housing entities.
     *
     * @return JsonResponse
     */
    public function allAction()
    {
        $dataResonseManager = $this->get('tools.data_response_manager');

        $housings = $this->get('ah.housing_manager')->getAllHousingEntity();
        $data = [
            'housings' => new DataResponseAdapter($housings, Housing::class),
        ];

        return new JsonResponse($dataResonseManager->createAdaptedResponseData($data), 200);
    }

    /**
     * Creates a new housing entity.
     *
     * @param Request $request Get the request for this action
     *
     * @return JsonResponse
     *
     * @throws \LogicException
     */
    public function newAction(Request $request)
    {
        $housing = new Housing();
        $form = $this->createForm(HousingTypeType::class, $housing, []);
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
     * @param int $id get the housing id
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

    /**
     * Displays a form to edit an existing housing entity.
     *
     * @param Request $request Get the request for this action
     * @param int     $id      Get the housing id
     *
     * @return JsonResponse
     *
     * @throws \ErrorException
     * @throws \LogicException
     */
    public function editAction(Request $request, int $id)
    {
        $dataResonseManager = $this->get('tools.data_response_manager');
        $housingManager = $this->get('ah.housing_manager');

        $housing = $housingManager->getHousingEntity($id);
        $form = $this->createForm(HousingTypeType::class, $housing, []);
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
