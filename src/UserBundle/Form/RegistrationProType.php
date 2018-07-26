<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\User;

class RegistrationProType extends RegistrationType
{
    /**
     * @param FormBuilderInterface $builder Get the form type builder
     * @param array                $options Get the form type options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => [
                        'class' => 'input-text',
                        'placeholder' => 'Email',
                    ]
                ]
            )
            ->add(
                'username',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'input-text',
                        'placeholder' => 'Username',
                    ]
                ]
            )
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'first_name' => 'pass',
                    'second_name' => 'confirm',
                    'first_options' => [
                        'attr' => [
                            'class' => 'input-text',
                            'placeholder' => 'Password',
                        ]
                    ],
                    'second_options' => [
                        'label' => false,
                        'attr' => [
                            'class' => 'input-text',
                            'placeholder' => 'Repeat Password',
                        ]
                    ],
                    'invalid_message' => 'fos_user.password.mismatch',
                ]
            )
            ->add('professionalInfos', RegistrationProfessionalType::class);
    }

    /**
     * @param OptionsResolver $resolver Get form type OptionsResolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'data_class' => User::class
            ]
        );
    }

    /**
     *
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'userbundle_personalinfo';
    }
}
