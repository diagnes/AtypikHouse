<?php

namespace HousingBundle\Form;

use Doctrine\ORM\EntityRepository;
use HousingBundle\Entity\Housing;
use HousingBundle\Entity\HousingType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\User;
use UserBundle\Enum\UserRoleEnum;

class HousingTeaserFormType extends AbstractType
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
                'visible',
                CheckboxType::class,
                [
                'label' => 'Visible',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                ]
            )
            ->add(
                'images',
                CollectionType::class,
                [
                'label' => false,
                'entry_type' => HousingImagesType::class,
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
            'data_class' => Housing::class,
            'attr' => [
                'class' => 'form-horizontal',
            ],
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'housingbundle_housing';
    }
}
