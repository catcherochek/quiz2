<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Task::class, 17, function (Task $task) {

            $task->setName($this->faker->unique(true)->title)
                ->setContent($this->faker->paragraph(2, true));
            // publish most articles
            if ($this->faker->boolean(50)) {
                $task->setIsDone(true);
            }

        });

        $manager->flush();
    }
}
