<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
class UserControllerTest extends WebTestCase
{
    /** @var EntityManagerInterface */
    public $entityManager;

    /**
     * @var KernelBrowser
     */
    public $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'j_dabok@me.com',
            'PHP_AUTH_PW' => 'Dabok@1977',
        ]);

        $this->client->disableReboot();

        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->entityManager->beginTransaction();
        $this->entityManager->getConnection()->setAutoCommit(false);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->rollback();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * @dataProvider getSecureUrls
     */
    public function testSecureURL(string $url)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertStringContainsString('/login', $client->getResponse()->getTargetUrl());

    }

    public function getSecureUrls()
    {
        yield['/users'];
    }

    public function testPageIsUp()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'j_dabok@me.com',
            'PHP_AUTH_PW' => 'Dabok@1977',
        ]);
        $crawler = $this->client->request('GET', '/users');
        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('h3')->count(),
            'Manage user'
        );
    }

    public function testListOfUserOnPage()
    {
        $crawler = $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
    }
}
