<?php

namespace App\Tests;

use App\Controller\SecurityController;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @internal
 */
class SecurityContollerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    public $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testPageLogin()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/login');

        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testLogOutUser()
    {
        $this->client->request('GET', '/logout');

        $crawler = $this->client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'BIENVENUE SUR TODO-LIST');
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPageLogoutIsSuccessful()
    {
        $client = static::createClient();
        $client->request('GET', '/logout');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testCorrectLogin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();
        $form['email'] = 'user@test.com';
        $form['password'] = 'password';
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        self::assertSame(200, $this->client->getResponse()->getStatusCode());
        self::assertSame(1, $crawler->filter('html:contains("Connexion")')->count());
    }

    public function testIncorrectEmail()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();
        $form['email'] = 'user@test.com';
        $form['password'] = 'password';
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        self::assertSame(1, $crawler->filter('html:contains("Cet Email n\'existe pas.")')->count());
    }

    public function testIncorrectPassword()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();
        $form['email'] = 'user';
        $form['password'] = 'badPassword';
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        self::assertSame(0, $crawler->filter('html:contains("Mot de passe incorrect")')->count());
    }

    public function testIncorrectUsernameAndPassword()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();
        $form['email'] = 'badUser';
        $form['password'] = 'badPassword';
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        self::assertSame(1, $crawler->filter('html:contains("Cet Email n\'existe pas.")')->count());
    }

    public function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $token = new UsernamePasswordToken('user', null, 'main', ['ROLE_USER']);

        $session->set('_security_'. 'main', serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    public function testLogout()
    {
        $chek = new SecurityController();
        $check = $chek->logout();
        self::assertNull( $check );
    }
}
