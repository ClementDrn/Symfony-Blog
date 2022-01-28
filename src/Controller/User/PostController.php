<?php

namespace App\Controller\User;

use App\Entity\Comment;
use App\Entity\Post;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_user_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager
            ->getRepository(Post::class)
            ->findAll();

        $length = count($posts);
        if ($length > 5)
            $length = 5;
        $latestPosts = array_slice($posts, 0, $length);

        return $this->render('post/index.user.html.twig', [
            'posts' => $latestPosts,
            'max_count' => $length
        ]);
    }

    /**
     * @Route("/{id}/{slug}", name="post_user_show", methods={"GET"})
     */
    public function show(Post $post, EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT c
             FROM App\Entity\Comment c
             WHERE c.post = :post
             ORDER BY c.createdAt DESC'
        )->setParameter('post', $post->getId());

        $comments = $query->getResult();

        return $this->render('post/show.user.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

}
