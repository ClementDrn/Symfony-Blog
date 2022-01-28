<?php

namespace App\Controller\User;

use DateTime;
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

        $now = new DateTime();
        foreach ($posts as $post) {
            if ($post->getUpdatedAt() > $now || $post->getCreatedAt() > $now || $post->getPublishedAt() > $now
                    || $post->getUpdatedAt() < $post->getCreatedAt()) {
                array_pop($posts, $post);
            }
        }
        
        usort($posts, function($a, $b) { return (($a->getUpdatedAt() < $b->getUpdatedAt()) ? -1 : 1); });

        $length = (count($posts) < 5 ? count($posts) : 5);
        
        $latestPosts = array_slice($posts, 0, $length);

        $query = $entityManager->createQuery('
            SELECT c.id, c.name, count(c.id) counter
            FROM App\Entity\Category c JOIN c.posts p
            GROUP BY c.id
            '
        );

        $categories = $query->getResult();

        return $this->render('post/index.user.html.twig', [
            'posts' => $latestPosts,
            'max_count' => $length,
            'categories' => $categories
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
