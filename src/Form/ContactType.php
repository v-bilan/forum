<?php

namespace App\Form;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,
                [
                    'attr' => ['class' => 'form-control'],
                ]
            )->add('subject', TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a subject',
                        ]),
                        new Length([
                            'min' => 4,
                            'minMessage' => 'Your subject should be at least {{ limit }} characters',
                            'maxMessage' => 'Your subject should be shorter {{ limit }} characters',
                            'max' => 256,
                        ])
                    ]
                ]
            )->add('message', TextareaType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a subject',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Your message should be at least {{ limit }} characters',
                            'maxMessage' => 'Your message should be shorter {{ limit }} characters',
                            'max' => 4000,
                        ])
                    ]
                ]
            )->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3([
                    'message' => 'karser_recaptcha3.message',
                    'messageMissingValue' => 'karser_recaptcha3.message_missing_value',
                ]),
            ])->add('save', SubmitType::class, [
                'attr' => ['class' => 'mt-1 btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
