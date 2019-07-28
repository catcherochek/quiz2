<?php

namespace App\Tests;

use App\Entity\User;
use App\Form\ConfirmPasswordType;
use App\Form\EmailToResetPasswordType;
use Symfony\Component\Form\Test\TypeTestCase;

class ConfirmPasswordTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $dataForm = [
            'password' => 'test123456',
        ];

        $objectToCompare = new User();
        $form = $this->factory->create(ConfirmPasswordType::class, $objectToCompare);
        $object = new User();
        $object
            ->setPassword('test123456');
        $form->submit($dataForm);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals($object->getPassword(), $objectToCompare->getPassword());
        $this->assertInstanceOf(User::class, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($dataForm) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
