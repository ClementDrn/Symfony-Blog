<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment_admin_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $comments = $entityManager
            ->getRepository(Comment::class)
            ->findAll();

        return $this->render('comment/index.admin.html.twig', [
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/{id}", name="comment_admin_show", methods={"GET"})
     */
    public function show(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute( 'comment_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/show.admin.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="comment_delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/{valid}/", name="comment_admin_validate", methods={"POST", "GET"})
     */
    public function validate(EntityManagerInterface $entityManager, Comment $comment, $valid)
    {
        $comment->setValid($valid == "1");
        $entityManager->flush();

        return $this->redirectToRoute('comment_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
