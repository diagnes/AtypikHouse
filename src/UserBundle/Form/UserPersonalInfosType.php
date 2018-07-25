<?php

namespace UserBundle\Form;

use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\UserPersonalInfos;

/**
 * Class UserPersonalInfosType
 * @SuppressWarnings(PHPMD)
 */
class UserPersonalInfosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder Get the builder Interface
     * @param array                $options Get the options for this form
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                'gender',
                TextType::class,
                [
                    'label' => 'Gender',
                    'attr' => [
                        'class' => 'input-text',
                        'placeholder' => 'Gender',
                    ]
                ]
            )
            ->add(
                'firstname',
                TextType::class,
                [
                    'label' => 'Firstname',
                    'attr' => [
                        'class' => 'input-text',
                        'placeholder' => 'Firstname',
                    ]
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                    'label' => 'Lastname',
                    'attr' => [
                        'class' => 'input-text',
                        'placeholder' => 'Lastname',
                    ]
                ]
            )
            ->add(
                'birthDate',
                DateType::class,
                [
                    'label' => 'BirthDate',
                    'attr' => [
                        'placeholder' => 'BirthDate',
                    ]
                ]
            )
            ->add(
                'birthLocation',
                TextType::class,
                [
                    'label' => 'BirthLocation',
                    'attr' => [
                        'class' => 'input-text',
                        'placeholder' => 'BirthLocation',
                    ]
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Description',
                    'attr' => [
                        'class' => 'input-text',
                        'placeholder' => 'Description',
                    ]
                ]
            )
            ->add(
                'phoneNumber',
                TextType::class,
                [
                    'label' => 'Phone Number',
                    'attr' => [
                        'class' => 'input-text',
                        'placeholder' => 'Phone Number',
                    ]
                ]
            )
            ->add(
                'profession',
                TextType::class,
                [
                    'label' => 'Profession',
                    'attr' => [
                        'class' => 'input-text',
                        'placeholder' => 'Profession',
                    ]
                ]
            )
            ->add(
                'nationality',
                TextType::class,
                [
                    'label' => 'Gender',
                    'attr' => [
                        'class' => 'input-text',
                        'placeholder' => 'Gender',
                    ]
                ]
            )
            ->add(
                'address',
                AddressType::class,
                [
                'class-input' => 'input-text'
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver Get form type OptionsResolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => UserPersonalInfos::class
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'userbundle_userpersonalinfos';
    }
}
