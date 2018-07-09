<?php

namespace AtypikHouseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationInfosType extends AbstractType
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
                'label' => 'Number of Resident'
                ]
            )
            ->add('startDate', DateType::class)
            ->add('endDate', DateType::class)
            ->add(
                'message',
                TextareaType::class,
                [
                'label' => 'You can send little message to your host'
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
            'data_class' => 'AtypikHouseBundle\Entity\ReservationInfos'
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
