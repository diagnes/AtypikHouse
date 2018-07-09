<?php

namespace UserBundle\Form;

use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Enum\RoleUserEnum;

class UserProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                'label' => "Nom d'utilisateur",
                'attr' => [
                    'class' => 'form-control'
                ]
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                'label' => "Email",
                'attr' => [
                    'class' => 'form-control'
                ]
                ]
            )
            ->add(
                'image',
                MediaType::class,
                [
                'label' => false,
                'provider' => 'sonata.media.provider.image',
                'required' => true,
                'context'  => 'default'
                ]
            )
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                'required' => false,
                'type' => PasswordType::class,
                'first_name' => 'pass',
                'second_name' => 'confirm',
                'first_options' => [
                    'label' => "Mot de passe",
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ],
                'second_options' => [
                    'label' => "Comfirmer mot de passe",
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ],
                'invalid_message' => 'fos_user.password.mismatch',
                ]
            )
            ->add('address', AddressType::class)
            ->add('personalInfo', PersonalInfoType::class);
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
            'data_class' => 'UserBundle\Entity\User'
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'userbundle_user';
    }
}
