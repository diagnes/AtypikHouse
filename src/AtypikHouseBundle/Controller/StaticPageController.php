<?php

namespace AtypikHouseBundle\Controller;

use AtypikHouseBundle\Entity\StaticPage;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Static Page Controller.
 *
 * In this controller user and anonymous can see static page
 *
 * PHP version 7.1
 *
 * @category  Controller
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 */
class StaticPageController extends Controller
{
    /**
     * Show the static Page targeted.
     *
     * @param string $slug Get the static page slug targeted
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function indexAction(string $slug)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        if ($this->isGranted('ROLE_ADMIN')) {
            $em->getFilters()->disable('deleted');
            $filter = [
                'slug' => $slug,
            ];
        } else {
            $filter = [
                'slug' => $slug,
                'visible' => true,
            ];
        }

        $staticPage = $em->getRepository(StaticPage::class)->findOneBy($filter);

        if (null === $staticPage) {
            throw new NotFoundHttpException('La page'.$slug.' n\'éxiste pas');
        }

        return $this->render(
            'AtypikHouseBundle:staticpage:show.html.twig',
            [
            'staticPage' => $staticPage,
            ]
        );
    }
}
