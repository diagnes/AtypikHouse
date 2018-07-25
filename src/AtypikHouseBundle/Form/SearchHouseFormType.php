<?php

namespace AtypikHouseBundle\Form;

use AtypikHouseBundle\Enum\ReservationStateEnum;
use Doctrine\ORM\EntityRepository;
use HousingBundle\Entity\Housing;
use HousingBundle\Enum\HousingStateEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AtypikHouseBundle\Entity\Reservation;
use UserBundle\Entity\User;

class SearchHouseFormType extends AbstractType
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
                'destination',
                TextType::class,
                [
                'label' => false,
                'attr' => [
                    'class' => 'field-input',
                ]
                ]
            )
            ->add(
                'resident',
                ChoiceType::class,
                [
                'label' => false,
                'choices' => array_flip($this->getArrayClients()),
                ]
            );
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
                'data_class' => null,
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
        return 'atypikhousebundle_se';
    }

    /**
     * Get an array for client
     * @return array
     */
    private function getArrayClients()
    {
        $guests = [];
        for ($i = 1; $i <= 10; $i++) {
            $guests[$i] = $i.' Guest';
        }

        return $guests;
    }
}
