<?php

namespace AtypikHouseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AtypikHouseBundle\Entity\ReservationInfos;

class ReservationInfosAdminFormType extends AbstractType
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
                'resident',
                IntegerType::class,
                [
                    'label' => 'Number of Resident',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ]
            )
            ->add(
                'startDate',
                DateType::class,
                [
                'widget' => 'single_text',
                'label' => false,
                'attr' => [
                    'class' => 'date-picker-start form-control',
                    'readonly' => 'readonly',
                ],
                'html5' => false,
                'format' =>'dd/MM/yyyy',
                ]
            )
            ->add(
                'endDate',
                DateType::class,
                [
                'widget' => 'single_text',
                'label' => false,
                'attr' => [
                    'class' => 'date-picker-end form-control',
                    'readonly' => 'readonly',
                ],
                'html5' => false,
                'format' =>'dd/MM/yyyy',
                ]
            )
            ->add(
                'message',
                TextareaType::class,
                [
                    'label' => 'Message to host',
                    'attr' => [
                        'class' => 'form-control',
                    ],
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
                'data_class' => ReservationInfos::class
            ]
        );
    }

    /**
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'atypikhousebundle_reservationinfos';
    }
}
