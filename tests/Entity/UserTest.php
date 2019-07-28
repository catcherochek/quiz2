<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\Token;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = new User();
    }

    public function testSettingId()
    {
        $this->user->getId();

        $this->assertEquals(null, $this->user->getId());
    }

    public function testSettingUsername()
    {
        $this->user->setUsername('jean');
        $this->assertSame('jean', $this->user->getUsername());
    }

    public function testSettingEmail()
    {
        $this->user->setEmail('email');
        $this->assertSame('email', $this->user->getEmail());
    }

    public function testSettingPassword()
    {
        $this->user->setPassword('password');
        $this->assertSame('password', $this->user->getPassword());
    }

    public function testSettingRole()
    {
        $role = ['ROLE_USER'];
        $this->user->setRoles($role);

        $this->assertSame($role, $this->user->getRoles());
    }

    public function testSettingCreatingAt()
    {
        $date = new \DateTime();
        $this->user->setCreatedAt($date);
        $this->assertSame($date, $this->user->getCreatedAt());
    }

    public function testSettingUpdatedAt()
    {
        $date = new \DateTime();
        $this->user->setUpdatedAt($date);
        $this->assertSame($date, $this->user->getUpdatedAt());
    }

    public function testAddingAndRemovedTask()
    {
        $task = new Task();

        $this->user->addTask($task);

        $this->assertEquals($task, $this->user->getTasks()[0]);

        $this->user->removeTask($task);

        $this->assertEquals(null, $this->user->getTasks()[0]);
    }

    public function testGettingSalt()
    {
        $this->user->getSalt();
        $this->assertEquals(null, $this->user->getSalt());
    }

    public function testEraseCredentials()
    {
        $this->user->eraseCredentials();
        $this->assertEquals(null, $this->user->eraseCredentials());
    }

    public function testIsAdmin()
    {
        $user = $this->user->isAdmin();
        $this->assertEquals($user, $this->user->isAdmin());
    }
}
