<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('startDate', null, [
                'widget' => 'single_text',
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'fullName',
                'choice_value' => 'id',
                'choices' => $options['employees'],
            ])
//            ->add('status', EntityType::class, [
//                'class' => Status::class,
//                'choice_label' => 'name',
//                'choice_value' => 'id'
//            ])
//            ->add('project', EntityType::class, [
//                'class' => Project::class,
//                'choice_label' => 'title',
//                'choice_value' => 'id'
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'employees' => [],
        ]);
    }
}
