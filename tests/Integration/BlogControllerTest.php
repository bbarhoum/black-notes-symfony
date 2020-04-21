<?php

namespace App\Tests\Integration;

use App\Entity\Post;
use App\Tests\BaseWebTestCase;
use Symfony\Component\Panther\PantherTestCase;

class BlogControllerTest extends PantherTestCase
{
    protected $client = null;
    protected $em = null;

    public function setUp(): void
    {
        $this->client = static::createPantherClient();

        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/blog/');

        $this->assertCount(
            10,
            $crawler->filter('article.post'),
            'The homepage displays the right number of posts.'
        );

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

        $this->assertNotEmpty(
            $crawler->filter('article.post > div > h1.post-title')->text(),
            'The post has the right title'
        );

        $oldComments = $crawler->filter('div.post-comment')->count();
        $this->assertGreaterThan(
            0,
            $oldComments,
            'The poste has the right comments number'
        );

        $this->assertCount(
            0,
            $crawler->filter('textarea#comment_content'),
            'Unauthenticated user can not comment post'
        );

        $loginLink = $crawler->filter('a.login')->link();
        $crawler = $this->client->click($loginLink);

        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'admin',
            'password' => 'admin',
        ]);

        $crawler = $this->client->submit($form);

        $this->assertNotEmpty(
            $crawler->filter('article.post > div > h1.post-title')->text(),
            'Redirection to the same post to comment'
        );

        $form = $crawler->selectButton('Add comment')->form([
            'comment[content]' => 'New test comment',
        ]);

        $crawler = $this->client->submit($form);

        $this->assertCount(
            $oldComments + 1,
            $crawler->filter('div.post-comment'),
            'The new comment is available'
        );
    }
}