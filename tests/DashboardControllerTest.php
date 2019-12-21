<?php

namespace App\Tests;


class DashboardControllerTest extends AuthenticatedTestCase
{
    public function testSomething()
    {
        $this->logInAsUser();

        $crawler = $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome to BlackNotes');
    }
}
