<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\User;

class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                'label' => false,
                'attr' => [
                    'placeholder' => "Email"
                ]
                ]
            )
            ->add(
                'username',
                TextType::class,
                [
                'label' => false,
                'attr' => [
                    'placeholder' => "Nom d'utilisateur"
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
                    'label' => false,
                    'attr' => [
                        'placeholder' => "Mot de passe"
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => "Confirmation"
                    ]
                ],
                'invalid_message' => 'fos_user.password.mismatch',
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'data_class' => User::class,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'user_registration';
    }
}
