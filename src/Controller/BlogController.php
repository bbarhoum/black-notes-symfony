<?php

namespace App\Controller;

use App\Bridge\PlaceholderBridge;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index")
     */
    public function index(PostRepository $postRepository)
    {

        $posts = $postRepository->findAll();

        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/{post}", name="blog_show_post")
     */
    public function showPost(Post $post)
    {

        return $this->render('blog/show_post.html.twig', [
            'post' => $post
        ]);
    }
}
