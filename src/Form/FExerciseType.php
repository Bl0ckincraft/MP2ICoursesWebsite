<?php

// src/Form/ExerciseType.php
namespace App\Form;

use App\Entity\Exercise;
use App\Entity\ExerciseType as ExType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [

                ]
            ])
            ->add('exerciseType', EntityType::class, [
                'class' => ExType::class,
                'choice_label' => 'displayName',
                'choice_value' => 'id',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.displayName', 'ASC');
                },
                'label' => 'Type d\'exercice',
                'attr' => [
                    'class' => 'w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent'
                ]
            ])
            ->add('number', IntegerType::class, [
                'label' => 'Numéro',
                'attr' => ['class' => 'bg-gray-700 border-gray-600 text-white rounded-lg']
            ])
            ->add('statement', TextareaType::class, [
                'label' => 'Énoncé (LaTeX)',
                'attr' => [
                    'class' => 'w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white h-32 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
                    'rows' => 5
                ]
            ])
            ->add('hints', CollectionType::class, [
                'label' => false,
                'entry_type' => TextareaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'prototype' => true,
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'latex-input',
                        'rows' => 3
                    ]
                ],
                'required' => false,
                'by_reference' => false,
                'attr' => [
                    'class' => 'hints-collection',
                    'data-prototype-name' => '__hints__'
                ]
            ])
            ->add('solutions', CollectionType::class, [
                'label' => false,
                'entry_type' => TextareaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'prototype' => true,
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'latex-input',
                        'rows' => 3
                    ]
                ],
                'required' => false,
                'by_reference' => false,
                'attr' => [
                    'class' => 'solutions-collection',
                    'data-prototype-name' => '__solutions__'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
        ]);
    }
}