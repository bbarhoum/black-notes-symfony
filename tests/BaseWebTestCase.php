<?php

namespace App\Tests;

use App\DataFixtures\PostFixtures;
use App\DataFixtures\TodoFixtures;
use App\DataFixtures\UsersFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseWebTestCase extends WebTestCase
{
    use FixturesTrait;

    protected $client = null;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->loadFixtures([PostFixtures::class, TodoFixtures::class, UsersFixtures::class]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->em->close();
        $this->em = null;
    }
}
