<?php

namespace HousingBundle\Form;

use HousingBundle\Entity\HousingDocument;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HousingDocumentType extends AbstractType
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
                'label' => 'Document name',
                'attr' => [
                    'class' => 'form-control',
                ],
                ]
            )
            ->add(
                'file',
                MediaType::class,
                [
                'label' => false,
                'provider' => 'sonata.media.provider.file',
                'required' => true,
                'context'  => 'default'
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
            'data_class' => HousingDocument::class
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'housingbundle_housingdocument';
    }
}
