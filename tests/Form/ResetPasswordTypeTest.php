<?php

namespace App\Tests;

use App\Entity\User;
use App\Form\ConfirmPasswordType;
use App\Form\EmailToResetPasswordType;
use App\Form\ResetPasswordType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class ResetPasswordTypeTest extends TypeTestCase
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
            'password' => [
                'first' => 'test123456',
                'second' => 'test123456'
            ],
        ];

        $objectToCompare = new User();
        $form = $this->factory->create(ResetPasswordType::class, $objectToCompare);
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
