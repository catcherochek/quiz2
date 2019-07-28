<?php

namespace App\Tests;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
class TaskControllerTest extends WebTestCase
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
        yield['/tasks'];
        yield['/tasks/create'];
        yield['/users'];
    }

    public function testPageIsUp()
    {
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('h1')->count(),
            'Liste des tâches'
        );
    }

    public function testNumberOfTaskOnPage()
    {
        $crawler = $this->client->request('GET', '/tasks');

        $this->assertCount(3, $crawler->filter('div.card'));
        $this->assertSame('Liste des tâches', $crawler->filter( 'h1')->text());
    }

    public function testCreateNewTask()
    {
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajoutez')->form([
            'task[name]' => 'From unit test',
            'task[content]' => 'Juste some new content',
        ]);
        $this->client->submit($form);

        /** @var Task $task */
        $task = $this->entityManager->getRepository(Task::class)->findOneBy([
            'name' => 'From unit test',
        ]);
        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        //$this->assertNotNull($task);
        $this->assertSame('From unit test', $task->getName());
        $this->assertSame('Juste some new content', $task->getContent());
    }

    public function testEditTask()
    {
        $crawler = $this->client->request('GET', '/task/38/edit');
        $form = $crawler->selectButton('Modifiez')->form([
            'task[name]' => 'Edit from unit test',
        ]);

        $this->client->submit($form);
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        /** @var Task $task */
        $task = $this->entityManager->getRepository(Task::class)->find(38);
        $this->assertSame('Edit from unit test', $task->getName());
    }

    public function testToggleTask()
    {
        $crawler = $this->client->request('GET', '/task/39/toggle');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        /** @var Task $task */
        $task = $this->entityManager->getRepository(Task::class)->find(39);
        $this->assertEquals(true, $task->getIsDone());
    }

    public function testDeleteTask()
    {
        $crawler = $this->client->request('GET', '/task/38/delete');
        $form = $crawler->selectButton('Supprimer')->form();

        $this->client->submit($form);

        /** @var Task $task */
        $task = $this->entityManager->getRepository(Task::class)->find(38);
        $this->assertNull($task);
    }
}
