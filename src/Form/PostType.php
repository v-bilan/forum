<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a title',
                        ]),
                        new Length([
                            'min' => 4,
                            'minMessage' => 'Your title should be at least {{ limit }} characters',
                            'max' => 255,
                        ]),
                    ],
                ])
            ->add('Content', TextareaType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a content',
                        ])
                    ],
                ]
            )
            ->add('author', EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'class' => User::class,
                'choice_label' => 'email',
            ])
            ->add('parent', EntityType::class, [
                'attr' => ['class' => 'form-control'],
                'class' => Post::class,
                'choice_label' => 'title',
                'required' => false,
                'placeholder' => 'Please choose a contact person',
                'empty_data' => null,
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'mt-1 btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
