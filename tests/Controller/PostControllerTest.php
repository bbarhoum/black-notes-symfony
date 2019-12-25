<?php


namespace App\Tests\Controller;


use App\Entity\Post;
use App\Tests\AuthenticatedTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostControllerTest extends AuthenticatedTestCase
{

    public function testPostIndex()
    {
        $this->logInAsUser();

        $crawler = $this->client->request('GET', '/post/');

        $this->assertPageTitleSame('BlackNotes | Posts', 'Post index page title');

        $posts = $this->em->getRepository(Post::class)->findBy(['createdBy' => $this->user]);
        $this->assertCount(
            count($posts),
            $crawler->filter('table > tbody tr'),
            'The right number of posts'
        );

        $linkNewPost = $crawler->selectLink('Create new post')->link();
        $crawler = $this->client->click($linkNewPost);

        $this->assertPageTitleSame('BlackNotes | New post', 'Create new post page loaded');
    }

    public function testCreatePost()
    {
        $this->logInAsUser();
        $crawler = $this->client->request('GET', '/post/new');
        $this->assertPageTitleSame('BlackNotes | New post', 'Create new post page loaded');

        $form = $crawler->selectButton('Save post')->form([]);
        $crawler = $this->client->submit($form);

        $this->assertEquals(
            [4, 1, 1, 1, 1],
            [
                $crawler->filter('span.form-error-message')->count(),
                $crawler->filter('span.form-error-message:contains("Title should not be blank.")')->count(),
                $crawler->filter('span.form-error-message:contains("Description should not be blank.")')->count(),
                $crawler->filter('span.form-error-message:contains("Content should not be blank.")')->count(),
                $crawler->filter('span.form-error-message:contains("Image should not be blank.")')->count(),
            ],
            'Validating form'
        );

        $oldPosts = $this->em->getRepository(Post::class)->findBy(['createdBy' => $this->user]);
        $form = $crawler->selectButton('Save post')->form([
            'post[title]' => 'Test new title',
            'post[description]' => 'Test new description',
            'post[content]' => 'Test content',
            'post[image]' => 'Test image',
        ]);
        $crawler = $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertPageTitleSame('BlackNotes | Post', 'Post show page loaded');
        $this->assertSelectorTextContains('h1', 'Test new title');

        $newPosts = $this->em->getRepository(Post::class)->findBy(['createdBy' => $this->user]);
        $this->assertCount(
            count($oldPosts) + 1,
            $newPosts,
            'New post added to the database'
        );
    }

    public function testShowPost()
    {
        $this->logInAsUser();
        $post = $this->em->getRepository(Post::class)->findOneBy(['createdBy' => $this->user]);

        $crawler = $this->client->request('GET', '/post/'.$post->getId());
        $this->assertPageTitleSame('BlackNotes | Post', 'Post show page loaded');
        $this->assertSelectorTextContains('h1', $post->getTitle());
        $this->assertSelectorExists('a[href="/post/"]', 'Can go back');
        $this->assertSelectorExists('a[href="/post/'.$post->getId().'/edit"]', 'Can edit');
    }

    public function testEditPost()
    {
        $this->logInAsUser();
        $post = $this->em->getRepository(Post::class)->findOneBy(['createdBy' => $this->user]);

        $crawler = $this->client->request('GET', '/post/'.$post->getId().'/edit');
        $this->assertPageTitleSame('BlackNotes | Edit post', 'Post edit page loaded');
        $form = $crawler->selectButton('Update post')->form([
            'post[title]' => 'Test edit title',
            'post[description]' => 'Test edit description',
        ]);
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Test edit title');
    }

    /**
     * @dataProvider getUrlForNonAuthenticatedUser
     */
    public function accessDeniedFonNonAuthenticatedUser(string $method, string $url)
    {
        $client = static::createClient();
        $client->request($method, $url);
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function getUrlForNonAuthenticatedUser()
    {
        yield ['GET', '/post/'];
        yield ['GET', '/post/new'];
        yield ['POST', '/post/new'];
        yield ['GET', '/post/1/edit'];
        yield ['POST', '/post/1/edit'];
        yield ['POST', '/post/1/show'];
        yield ['POST', '/post/1/delete'];
    }
}