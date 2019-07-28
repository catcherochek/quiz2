<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    public $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testClickButtonInscription()
    {

        $crawler = $this->client->request('GET', '/');

        self::assertContainS('/registration', $crawler->filter('a')->extract(['href']));
        $link = $crawler->selectLink('Inscription')->link();
        $crawler = $this->client->click($link);

        self::assertStringContainsString('Inscription', $crawler->filter('h1')->html());
    }

    public function testClickButtonConnexion()
    {

        $crawler = $this->client->request('GET', '/');

        self::assertContains('/login', $crawler->filter('a')->extract(['href']));
        $link = $crawler->selectLink('Connexion')->link();
        $crawler = $this->client->click($link);

        self::assertStringContainsString('Connexion', $crawler->filter('h1')->html());
    }

    public function testTextOnPage()
    {
        $crawler= $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('BIENVENUE SUR TODO-LIST', $crawler->filter('h1')->text());
    }
}
