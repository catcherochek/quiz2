<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use App\Form\ConfirmPasswordType;
use App\Form\EmailToResetPasswordType;
use App\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $dataForm = [
            'name' => 'ma tache',
            'content' => 'le contenu de ma tâche'
        ];

        $objectToCompare = new Task();
        $form = $this->factory->create(TaskType::class, $objectToCompare);
        $object = new Task();
        $object
            ->setName('ma tache')
            ->setContent('le contenu de ma tâche')
        ;
        $form->submit($dataForm);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals($object->getName(), $objectToCompare->getName());
        $this->assertEquals($object->getContent(), $objectToCompare->getContent());
        $this->assertInstanceOf(Task::class, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($dataForm) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
