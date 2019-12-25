<?php

namespace App\Tests\Controller;

use App\Entity\Post;
use App\Tests\BaseWebTestCase;

class BlogControllerTest extends BaseWebTestCase
{
    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/blog/');

        $posts = $this->em->getRepository(Post::class)->findAll();

        $this->assertCount(
            count($posts),
            $crawler->filter('article.post'),
            'The homepage displays the right number of posts.'
        );
    }

    public function testNavigateToPost()
    {
        $crawler = $this->client->request('GET', '/blog/');
        $postLink = $crawler->filter('article.post > .card-body > h5.card-title a')->link();

        $crawler = $this->client->click($postLink);

        $this->assertCount(
            1,
            $crawler->filter('article.post'),
            'Article shown correctly from list'
        );

        $this->assertCount(
            4,
            $crawler->filter('.recent-post > h5.post-title'),
            'The right number of recent article'
        );
    }

    public function testShowPost()
    {
        $post = $this->em->getRepository(Post::class)->findOneBy([]);
        $crawler = $this->client->request('GET', '/blog/' . $post->getSlug());

        $this->assertSame(
            $post->getTitle(),
            $crawler->filter('article.post > div > h1.post-title')->text(),
            'The post has the right title'
        );

        $this->assertCount(
            count($post->getComments()),
            $crawler->filter('div.post-comment'),
            'The poste has the right comments number'
        );

        $this->assertCount(
            0,
            $crawler->filter('textarea#comment_content'),
            'Unauthenticated user can not comment post'
        );
    }

    public function testAddComment()
    {
        $post = $this->em->getRepository(Post::class)->findOneBy([]);
        $crawler = $this->client->request('GET', '/blog/' . $post->getSlug());

        $loginLink = $crawler->filter('a.login')->link();
        $crawler = $this->client->click($loginLink);

        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'admin',
            'password' => 'admin',
        ]);

        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertSame(
            $post->getTitle(),
            $crawler->filter('article.post > div > h1.post-title')->text(),
            'Redirection to the same post to comment'
        );

        $form = $crawler->selectButton('Add comment')->form([
            'comment[content]' => 'New test comment',
        ]);

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertCount(
            count($post->getComments()) + 1,
            $crawler->filter('div.post-comment'),
            'The new comment is available'
        );
    }

}