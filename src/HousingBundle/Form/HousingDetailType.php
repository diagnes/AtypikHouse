<?php

namespace HousingBundle\Form;

use HousingBundle\Entity\HousingDetail;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HousingDetailType extends AbstractType
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
                'icon',
                MediaType::class,
                [
                    'label' => 'Upload an image',
                    'provider' => 'sonata.media.provider.image',
                    'required' => true,
                    'context'  => 'icon'
                ]
            )
            ->add(
                'label',
                TextType::class,
                [
                'label' => 'Label',
                'attr' => [
                    'class' => 'form-control',
                ],
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
            'data_class' => HousingDetail::class
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'housingbundle_housingdetail';
    }
}
