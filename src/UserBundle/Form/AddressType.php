<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\Address;

class AddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder Get the builder Interface
     * @param array                $options Get the options for this form
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'streetNumber',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'StreetNumber',
                ]
                ]
            )
            ->add(
                'address',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Address',
                ]
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'City',
                ]
                ]
            )
            ->add(
                'postalcode',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Postal Code',
                ]
                ]
            )
            ->add(
                'country',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Country',
                ]
                ]
            );
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
            'data_class' => Address::class
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'userbundle_address';
    }
}
