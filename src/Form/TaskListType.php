<?php

namespace App\Form;

use App\Entity\TasksList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TaskListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name: ',
                'required' => true,
                'help' => 'minimum 3 lettres, maximum 30 lettres'
            ])
            ->add('desc', TextareaType::class, [
                'label' => 'Description: ',
                'required' => true,
                'data' => "empty",
                'help' => 'minimum 10 lettres, maximum 400 lettres'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TasksList::class,
        ]);
    }
}
