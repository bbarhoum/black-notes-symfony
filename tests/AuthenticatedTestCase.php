<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticatedTestCase extends BaseWebTestCase
{
    protected $user = null;

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

        $this->client = null;
        $this->user = null;
    }
}
