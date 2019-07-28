<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private $task;

    public function setUp(): void
    {
        parent::setUp();
        $this->task = new Task();
    }

    public function testSettingId()
    {
        $this->task->getId();

        $this->assertEquals(null, $this->task->getId());
    }

    public function testSettingName()
    {
        $this->task->setName('task');
        $this->assertSame('task', $this->task->getName());
    }

    public function testSettingContent()
    {
        $this->task->setContent('task');
        $this->assertSame('task', $this->task->getContent());
    }

    public function testSettingCreatingAt()
    {
        $date = new \DateTime();
        $this->task->setCreatedAt($date);
        $this->assertSame($date, $this->task->getCreatedAt());
    }

    public function testSettingUpdatedAt()
    {
        $date = new \DateTime();
        $this->task->setUpdatedAt($date);
        $this->assertSame($date, $this->task->getUpdatedAt());
    }

    public function testSettingIsDone()
    {
        $this->assertFalse($this->task->getIsDone());
        $this->task->setIsDone(true);
        $this->assertTrue(true);
    }

    public function testToggle()
    {
        $this->task->toggle(true);
        $this->assertTrue(true);
    }

    public function testSettingUser()
    {
        $user = new User();
        $this->task->setUser($user);
        $this->assertSame($user, $this->task->getUser());
    }
}
