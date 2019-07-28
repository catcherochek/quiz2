<?php

namespace App\Tests;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class RegistrationTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData()
    {
        $dataForm = [
            'email' => 'test@gmail.com',
            'password' => [
                'first' => 'password123',
                'second' => 'password123'
            ],
            'username' => 'jlbokass2',

        ];

        $objectToCompare = new User();
        $form = $this->factory->create(RegistrationType::class, $objectToCompare);
        $object = new User();
        $object
            ->setEmail('test@gmail.com')
            ->setPassword('password123')
            ->setUsername('jlbokass2')

        ;
        $form->submit($dataForm);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals($object->getEmail(), $objectToCompare->getEmail());
        $this->assertEquals($object->getUsername(), $objectToCompare->getUsername());
        $this->assertEquals($object->getPassword(), $objectToCompare->getPassword());
        $this->assertInstanceOf(User::class, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($dataForm) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
