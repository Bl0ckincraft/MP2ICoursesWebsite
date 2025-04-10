<?php

// src/Form/ChapterType.php
namespace App\Form;

use App\Entity\Chapter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChapterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Identifiant',
                'attr' => [
                    'class' => 'w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
                    'placeholder' => 'ex: matrices'
                ]
            ])
            ->add('number', IntegerType::class, [
                'label' => 'Numéro',
                'attr' => [
                    'class' => 'w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
                ]
            ])
            ->add('displayName', TextType::class, [
                'label' => 'Nom affiché',
                'attr' => [
                    'class' => 'w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
                    'placeholder' => 'ex: Matrices et déterminants'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chapter::class,
        ]);
    }
}