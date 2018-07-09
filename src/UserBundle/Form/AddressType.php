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
     * @param FormBuilderInterface $builder
     * @param array                $options
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
                'label' => 'Street',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
                ]
            )
            ->add(
                'address',
                TextType::class,
                [
                'label' => 'Address',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                'label' => 'City',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
                ]
            )
            ->add(
                'postalcode',
                TextType::class,
                [
                'label' => 'Postal code',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
                ]
            )
            ->add(
                'country',
                TextType::class,
                [
                'label' => 'Country',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
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
