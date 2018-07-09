<?php

namespace AtypikHouseBundle\Form;

use HousingBundle\Entity\Housing;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AtypikHouseBundle\Entity\Reservation;
use UserBundle\Entity\User;

class ReservationType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'housing',
                EntityType::class,
                [
                'label' => 'Housing',
                'class' => Housing::class,
                ]
            )
            ->add(
                'user',
                EntityType::class,
                [
                'label' => 'Utilisateur',
                'class' => User::class,
                ]
            )
            ->add('reservationInfos', ReservationInfosType::class);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => Reservation::class
            ]
        );
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix()
    {
        return 'atypikhousebundle_reservation';
    }
}
