<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index")
     */
    public function index(PostRepository $posts, TagRepository $tags, UserRepository $users, Request $request)
    {



        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
        }

        $user = null;
        if ($request->query->has('writer')) {
            $user = $users->findOneBy(['username' => $request->query->get('writer')]);
        }

        $posts = $posts->findLatest($tag, $user);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/{slug}", name="blog_show_post")
     */
    public function showPost(Post $post): Response
    {
        return $this->render('blog/show_post.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/{slug}/comment", name="blog_comment_add")
     */
    public function addComment(Post $post, Request $request)
    {
        $comment = new Comment();
        $comment->setCreatedBy($this->getUser());
        $post->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('blog_show_post', ['slug' => $post->getSlug()]);
        }

        return $this->render('blog/comment_form_error.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    public function commentForm(Post $post): Response
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('blog/_comment_form.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    public function recentPosts(PostRepository $postRepository): Response
    {
        $recentPosts = $postRepository->findBy([], ['createdAt' => 'desc'], 4);

        return $this->render('blog/_recent_posts.html.twig', [
            'posts' => $recentPosts
        ]);
    }
}
