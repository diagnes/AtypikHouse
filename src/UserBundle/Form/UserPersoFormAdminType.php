<?php

namespace UserBundle\Form;

use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Enum\UserGenderEnum;
use UserBundle\Entity\UserPersonalInfos;

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
                    'label' => 'Profile Picture',
                    'provider' => 'sonata.media.provider.image',
                    'required' => true,
                    'context'  => 'user',
                ]
            )
            ->add(
                'gender',
                ChoiceType::class,
                [
                    'label' => 'Gender',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => array_flip(UserGenderEnum::toAssoc())
                ]
            )
            ->add(
                'firstname',
                TextType::class,
                [
                    'label' => 'Firstname',
                    'attr' => [
                        'class' => 'form-control',
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
                    'widget' => 'single_text',
                    'label' => 'BirthDate',
                    'attr' => [
                        'class' => 'date-picker form-control',
                    ],
                    'html5' => false,
                    'format' =>'dd/MM/yyyy',
                ]
            )
            ->add(
                'birthLocation',
                TextType::class,
                [
                    'label' => 'BirthLocation',
                    'attr' => [
                        'class' => 'form-control google-autocomplete',
                        'data-types' => "['geocode']"
                    ]
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Description',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'phoneNumber',
                TextType::class,
                [
                    'label' => 'Phone Number',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'profession',
                TextType::class,
                [
                    'label' => 'Profession',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'nationality',
                CountryType::class,
                [
                    'label' => 'Nationality',
                    'attr' => [
                        'class' => 'chosen-select',
                    ],
                    'empty_data' => 'FR',
                ]
            )
            ->add(
                'address',
                AddressType::class,
                [
                'label' => 'Address'
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
