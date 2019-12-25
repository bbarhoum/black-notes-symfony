<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class AuthenticatedTestCase extends WebTestCase
{
    protected $client = null;
    protected $user = null;

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
    }

    protected function logInAsUser()
    {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'main';
        $firewallContext = 'main';

        $this->user = $this->client->getContainer()
            ->get('doctrine')->getRepository(User::class)
            ->findOneBy(['username' => 'user']);

        $token = new UsernamePasswordToken($this->user, null, $firewallName, ['ROLE_USER']);

        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->em->close();
        $this->em = null;
        $this->client = null;
        $this->user = null;
    }
}
