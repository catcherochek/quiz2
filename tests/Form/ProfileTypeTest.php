<?php

namespace App\Tests;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\Form\Test\TypeTestCase;

class ProfileTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $dataForm = [
            'email' => 'jdabok@me.com',
            'username' => 'jlbokass'
        ];

        $objectToCompare = new User();
        $form = $this->factory->create(ProfileType::class, $objectToCompare);
        $object = new User();
        $object
            ->setEmail('jdabok@me.com')
            ->setUsername('jlbokass')
        ;
        $form->submit($dataForm);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals($object->getEmail(), $objectToCompare->getEmail());
        $this->assertEquals($object->getUsername(), $objectToCompare->getUsername());
        $this->assertInstanceOf(User::class, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($dataForm) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
