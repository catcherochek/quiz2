<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PWDChangeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //TODO: Password check Form
            ->add('oldpassword',
                PasswordType::class,
                array(
                   // 'widget' => 'single_text',
                    'label'  => 'Type Old Pass',))
            ->add('newpassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'both passwords must match.',
                'first_options' => [
                    'label' => 'Password: ',
                    'help' => 'minimum 6 lettres and 1 digit.'
                ], 'second_options' => [
                    'label' => 'Type the password second time: ',
                    'help' => 'the same password.'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => User::class,
        ]);
    }
}
