<?php

namespace AtypikHouseBundle\Controller;

use AtypikHouseBundle\Entity\StaticPage;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AtypikHouseBundle\Form\StaticPageType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Static Page Admin Controller.
 *
 * In this controller admin can manage all static page
 *
 * PHP version 7.1
 *
 * @category  Controller
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class StaticPageAdminController extends Controller
{
    /**
     * Lists all staticPage entities.
     *
     * @return Response
     */
    public function indexAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('deleted');
        $staticpages = $em->getRepository('AtypikHouseBundle:StaticPage')->findAll();

        return $this->render(
            'AtypikHouseBundle:staticpage-admin:list.html.twig',
            [
            'staticpages' => $staticpages,
            ]
        );
    }

    /**
     * Creates a new staticPage entity.
     *
     * @param Request $request Get the request
     *
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $staticPage = new Staticpage();
        $form = $this->createForm(StaticPageType::class, $staticPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($staticPage);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', sprintf('The Static Page %s has been created', $staticPage->getSlug()));
            return $this->redirectToRoute('atypikhouse_admin_staticpage_edit', ['slug' => $staticPage->getSlug()]);
        }

        return $this->render(
            'AtypikHouseBundle:staticpage-admin:form.html.twig',
            [
            'staticpage' => $staticPage,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing staticPage entity.
     *
     * @param Request $request Get the request
     * @param string  $slug    Get the static Page slug
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, string $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $staticPage = $em->getRepository(StaticPage::class)->findOneBy(['slug' => $slug]);
        if (null === $staticPage) {
            throw new NotFoundHttpException('La page'.$slug.' demander n\'éxiste pas');
        }
        $form = $this->createForm(StaticPageType::class, $staticPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', sprintf('The Static Page %s has been edited', $staticPage->getSlug()));
            return $this->redirectToRoute('atypikhouse_admin_staticpage_edit', ['slug' => $staticPage->getSlug()]);
        }

        return $this->render(
            'AtypikHouseBundle:staticpage-admin:form.html.twig',
            [
            'staticpage' => $staticPage,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a staticpage entity.
     *
     * @param string $slug Get the static Page slug
     *
     * @return RedirectResponse
     */
    public function deleteAction(string $slug): RedirectResponse
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $staticpage = $em->getRepository(StaticPage::class)->findOneBy(['slug' => $slug]);
            if (null === $staticpage) {
                throw new NotFoundHttpException('La page'.$slug.' demander n\'éxiste pas');
            }
            $staticpage->setDeletedAt(new \DateTime());
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The Static Page %s has been deleted', $staticpage->getSlug()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atypikhouse_admin_staticpage_index');
    }

    /**
     * Undelete a staticpage entity.
     *
     * @param string $slug Get the static Page slug
     *
     * @return RedirectResponse
     */
    public function undeleteAction(string $slug): RedirectResponse
    {
        try {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $em->getFilters()->disable('deleted');

            $staticpage = $em->getRepository(StaticPage::class)->findOneBy(['slug' => $slug]);
            if (null === $staticpage) {
                throw new NotFoundHttpException('La page '.$slug.' demander n\'éxiste pas');
            }
            $staticpage->setDeletedAt(null);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The Static Page %s has been undeleted', $staticpage->getSlug()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atypikhouse_admin_staticpage_index');
    }
}
