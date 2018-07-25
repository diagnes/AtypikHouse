<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\UserProfessionalInfos;

class UserProFormAdminType extends AbstractType
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
                'siret',
                TextType::class,
                [
                    'label' => 'Siret',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'workNumber',
                TextType::class,
                [
                    'label' => 'WorkNumber',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'entreprise',
                TextType::class,
                [
                    'label' => 'Enterprise',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver Get form type OptionsResolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UserProfessionalInfos::class
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
