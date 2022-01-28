<?php

namespace App\Controller\User;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post/{id}/{slug}/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment_user_index", methods={"GET"})
     */
    public function index(Post $post, EntityManagerInterface $entityManager): Response
    {
        $comments = $entityManager
            ->getRepository(Comment::class)
            ->findAll();

        return $this->render('comment/index.user.html.twig', [
            'comments' => $comments,
            'post' => $post
        ]);
    }

    /**
     * @Route("/new", name="comment_new", methods={"GET", "POST"})
     */
    public function new(Post $post, $slug, Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setValid(false);
            $comment->setCreatedAt(new DateTime());
            $comment->setPost($post);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute(
                'comment_user_index', [
                    "id" => $id,
                    "slug" => $slug
                ], 
                Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'post' => $post
        ]);
    }

    /**
     * @Route("/{comment_id}/", name="comment_user_show", methods={"GET"})
     */
    public function show(Post $post, $comment_id): Response
    {
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $commentRepository->find($comment_id);

        return $this->render('comment/show.user.html.twig', [
            'comment' => $comment,
        ]);
    }

}
