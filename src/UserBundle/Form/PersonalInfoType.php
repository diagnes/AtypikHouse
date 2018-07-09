<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonalInfoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'firstname',
                TextType::class,
                [
                'label' => "Prénom",
                'attr' => [
                    'class' => 'form-control'
                ]
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                'label' => "Nom",
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
            'data_class' => 'UserBundle\Entity\PersonalInfo'
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'userbundle_personalinfo';
    }
}
