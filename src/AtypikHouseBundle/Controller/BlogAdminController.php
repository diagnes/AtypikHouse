<?php

namespace AtypikHouseBundle\Controller;

use AtypikHouseBundle\Entity\Blog;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use AtypikHouseBundle\Form\BlogType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Blog Admin controller.
 *
 * In this controller admin can manage all blog
 *
 * PHP version 7.1
 *
 * @category  Controller
 * @author    Diagne Stéphane <diagne.stephane@gmail.com>
 * @copyright 2018
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class BlogAdminController extends Controller
{
    /**
     * Lists all blog entities.
     *
     * @return Response
     */
    public function indexAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('deleted');
        
        $blogs = $em->getRepository('AtypikHouseBundle:Blog')->findAll();

        return $this->render(
            'AtypikHouseBundle:blog-admin:list.html.twig',
            [
            'blogs' => $blogs,
            ]
        );
    }

    /**
     * Creates a new blog entity.
     *
     * @param Request $request Get the request
     *
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $blog->setAuthor($this->getUser());
            $em->persist($blog);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', sprintf('The Blog %s has been created', $blog->getSlug()));
            return $this->redirectToRoute('atypikhouse_blog_admin_edit', ['slug' => $blog->getSlug()]);
        }

        return $this->render(
            'AtypikHouseBundle:blog-admin:form.html.twig',
            [
            'blog' => $blog,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing blog entity.
     *
     * @param Request $request Get the request
     * @param string  $slug    Get the targeted blog
     *
     * @return RedirectResponse|Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Request $request, string $slug)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('deleted');

        $blog = $em->getRepository(Blog::class)->findOneBy(['slug' => $slug]);
        if (null === $blog) {
            throw new NotFoundHttpException('The page '.$slug.' does\'t exist');
        }
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The blog %s has been edited', $blog->getSlug()));

            return $this->redirectToRoute('atypikhouse_blog_admin_edit', ['slug' => $blog->getSlug()]);
        }

        return $this->render(
            'AtypikHouseBundle:blog-admin:form.html.twig',
            [
            'blog' => $blog,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a blog entity.
     *
     * @param string $slug Get the static Page slug
     *
     * @return RedirectResponse
     */
    public function deleteAction(string $slug): RedirectResponse
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $blog = $em->getRepository(Blog::class)->findOneBy(['slug' => $slug]);
            if (null === $blog) {
                throw new NotFoundHttpException('The page '.$slug.' does\'t exist');
            }
            $blog->setDeletedAt(new \DateTime());
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The Blog %s has been deleted', $blog->getSlug()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atypikhouse_blog_admin_index');
    }

    /**
     * Undelete a blog entity.
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

            $blog = $em->getRepository(Blog::class)->findOneBy(['slug' => $slug]);
            if (null === $blog) {
                throw new NotFoundHttpException('The page '.$slug.' does\'t exist');
            }
            $blog->setDeletedAt(null);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('The Blog %s has been undeleted', $blog->getSlug()));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirectToRoute('atypikhouse_blog_admin_index');
    }
}
