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
     * @param FormBuilderInterface $builder Get the form type builder
     * @param array                $options Get the form type options
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
                    'attr' => [
                        'class' => 'input-text',
                        'placeholder' => 'Username',
                    ]
                ]
            )
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
            ->add('personalInfo', UserPersonalInfosType::class);
    }

    /**
     * @param OptionsResolver $resolver Get the form resolver options
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
