<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Task;

class TaskVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['LIST','EDIT', 'DELETE', 'TOGGLE'])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute($attribute, $task, TokenInterface $token)
    {
        /** @var Task $task */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'LIST':
                if ($task->getUser() === $user) {
                    return true;
                }
                break;

            case 'EDIT':
                if ($task->getUser() === $user) {
                    return true;
                }
                break;
            case 'DELETE':
                if ($task->getUser() === $user) {
                    return true;
                }
                break;
            case 'TOGGLE':
                if ($task->getUser() === $user) {
                    return true;
                }
                break;
        }

        return false;
    }
}
