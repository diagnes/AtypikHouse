<?php

namespace HousingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HousingBundle\Entity\HousingNotationValue;

class HousingNotationValueType extends AbstractType
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
                'value',
                IntegerType::class,
                [
                'label' => false,
                'attr' => [
                    'class' => 'input-text',
                    'min' => 1,
                    'max' => 5,
                ]
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver Get the form resolver options
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => HousingNotationValue::class
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'housingbundle_housingnotationvalue';
    }
}
