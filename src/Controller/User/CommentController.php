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
     * @Route("/new", name="comment_user_new", methods={"GET", "POST"})
     */
    public function new(Post $post, Request $request, EntityManagerInterface $entityManager): Response
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
                'post_user_show', 
                [
                    "id" => $post->getId(),
                    "slug" => $post->getSlug()
                ], 
                Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.user.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'post' => $post
        ]);
    }

}
