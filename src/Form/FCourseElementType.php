<?php

namespace App\Form;

use App\Entity\CourseElement;
use App\Entity\CourseElementType as ElementType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FCourseElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('elementType', EntityType::class, [
                'class' => ElementType::class,
                'choice_label' => 'displayName',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.displayName', 'ASC');
                },
                'label' => 'Type d\'élément',
                'attr' => ['class' => 'bg-gray-700 border-gray-600 text-white rounded-lg']
            ])
            ->add('number', IntegerType::class, [
                'label' => 'Numéro',
                'attr' => ['class' => 'bg-gray-700 border-gray-600 text-white rounded-lg']
            ])
            ->add('statement', TextareaType::class, [
                'label' => 'Énoncé (LaTeX)',
                'attr' => [
                    'class' => 'bg-gray-700 border-gray-600 text-white rounded-lg h-32',
                    'data-controller' => 'latex-editor'
                ]
            ])
            ->add('details', CollectionType::class, [
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
                    'class' => 'details-collection',
                    'data-prototype-name' => '__details__'
                ]
            ])
            ->add('proofs', CollectionType::class, [
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
                    'class' => 'proofs-collection',
                    'data-prototype-name' => '__proofs__'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CourseElement::class,
        ]);
    }
}
