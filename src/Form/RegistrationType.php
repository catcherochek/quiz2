<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email: ',
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'both passwords must match.',
                'first_options' => [
                    'label' => 'Password: ',
                    'help' => 'minimum 6 lettres and 1 digit.'
                ], 'second_options' => [
                    'label' => 'Type the password and try again: ',
                    'help' => 'the same password.'
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Username: ',
                'required' => true,
                'help' => 'Will serve to identify you on the site.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
