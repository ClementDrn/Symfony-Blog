<?php

namespace App\Controller\User;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\CategoryType;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("/{id}", name="category_user_show", methods={"GET"})
     */
    public function show(Category $category, EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery('
            SELECT p.id, p.title, p.slug, p.description, p.updatedAt, p.createdAt, p.publishedAt
            FROM App\Entity\Post p JOIN p.categories c
            WHERE c.id = :category
            '
        )->setParameter('category', $category->getId());

        $posts = $query->getResult();

        $now = new DateTime();
        foreach ($posts as $post) {
            $updatedAt = $post["updatedAt"];
            $createdAt = $post["createdAt"];
            $publishedAt = $post["publishedAt"];
            if ($updatedAt > $now || $createdAt > $now || $publishedAt > $now
                    || $updatedAt < $createdAt) {
                array_pop($posts, $post);
            }
        }

        return $this->render('category/show.user.html.twig', [
            'category' => $category,
            'posts' => $posts
        ]);
    }

}
