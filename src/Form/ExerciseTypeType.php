<?php

// src/Form/ExerciseTypeType.php
namespace App\Form;

use App\Entity\ExerciseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Identifiant',
                'attr' => [
                    'class' => 'w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
                    'placeholder' => 'ex: td'
                ]
            ])
            ->add('displayName', TextType::class, [
                'label' => 'Nom affiché',
                'attr' => [
                    'class' => 'w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
                    'placeholder' => 'ex: Travaux Dirigés'
                ]
            ])
            ->add('icon', TextType::class, [
                'label' => 'Icône (Material)',
                'attr' => [
                    'class' => 'w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
                    'placeholder' => 'ex: favorite'
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExerciseType::class,
        ]);
    }
}