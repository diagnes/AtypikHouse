<?php

namespace UserBundle\Form;

use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserPersoFormAdminType
 * @SuppressWarnings(PHPMD)
 */
class UserPersoFormAdminType extends AbstractType
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
                    'label' => 'Photo profil',
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
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'firstname',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'label' => 'Firstname',
                    ]
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                    'label' => 'Lastname',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'birthDate',
                DateType::class,
                [
                    'label' => 'BirthDate',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'birthLocation',
                TextType::class,
                [
                    'label' => 'BirthLocation',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Gender',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'phoneNumber',
                TextType::class,
                [
                    'label' => 'Gender',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'profession',
                TextType::class,
                [
                    'label' => 'Gender',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'nationality',
                TextType::class,
                [
                    'label' => 'Gender',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add('address', AddressType::class);
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
                'data_class' => 'UserBundle\Entity\UserPersonalInfos'
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
