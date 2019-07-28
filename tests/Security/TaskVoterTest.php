<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use App\Security\Voter\TaskVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TaskVoterTest extends TestCase
{
    /**
     * @dataProvider voterProvider
     *
     */
    public function testCarVoter($user, $expected)
    {
        $voter = new TaskVoter();
        $task = new Task();

        $token = new AnonymousToken('secret', 'anonymous');

        if($user) {
            $token = new UsernamePasswordToken($user, 'credentials', 'memory');
            $task->setUser($user);
        }

        $this->assertSame($expected, $voter->vote($token, $task, ['LIST']));
        $this->assertSame($expected, $voter->vote($token, $task, ['EDIT']));
        $this->assertSame($expected, $voter->vote($token, $task, ['TOGGLE']));
        $this->assertSame($expected, $voter->vote($token, $task, ['DELETE']));
    }

    public function voterProvider()
    {
        $userOne = $this->createMock(User::class);
        $userOne->method('getId')->willReturn(1);

        return [
            [$userOne, 1],
            [null, -1]
        ];
    }
}
