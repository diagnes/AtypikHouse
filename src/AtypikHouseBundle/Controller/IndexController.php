<?php

namespace AtypikHouseBundle\Controller;

use AtypikHouseBundle\Entity\Blog;
use AtypikHouseBundle\Enum\NotificationTypeEnum;
use AtypikHouseBundle\Form\SearchHouseFormType;
use HousingBundle\Entity\Housing;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UserBundle\Entity\UserNotification;

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
        $blogs = $em->getRepository(Blog::class)->getllAvailableBlog(4);
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
            'housingsTotal' => $housingsTotal,
                'blogs' => $blogs,
            ]
        );
    }

    /**
     * Set notification has viewed
     *
     * @param Request $request Get the request
     * @param int     $id      Get tne notification id
     *
     * @return RedirectResponse
     */
    public function notificationTransferAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $notif = $em->getRepository(UserNotification::class)->findOneBy(['id' => $id]);
        if (null === $notif) {
            throw new NotFoundHttpException('Cette notification n\'éxiste pas');
        }
        $notif->setState(0);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
