<?php

namespace HousingBundle\Form;

use HousingBundle\Entity\HousingDetail;
use HousingBundle\Entity\HousingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HousingTypeType extends AbstractType
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
                'name',
                TextType::class,
                [
                'label' => 'Name',
                'attr' => [
                    'class' => 'form-control',
                ],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                ],
                ]
            )
            ->add(
                'details',
                CollectionType::class,
                [
                'label' => false,
                'entry_type' => HousingDetailType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
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
            'data_class' => HousingType::class
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'housingbundle_housingtype';
    }
}
