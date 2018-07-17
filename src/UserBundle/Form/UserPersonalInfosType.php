<?php

namespace UserBundle\Form;

use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserPersonalInfosType
 * @SuppressWarnings(PHPMD)
 */
class UserPersonalInfosType extends AbstractType
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
                'image',
                MediaType::class,
                [
                'label' => false,
                'provider' => 'sonata.media.provider.image',
                'required' => true,
                'context'  => 'default'
                ]
            )
            ->add(
                'gender',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Gender',
                ]
                ]
            )
            ->add(
                'firstname',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Firstname',
                ]
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Lastname',
                ]
                ]
            )
            ->add(
                'birthDate',
                DateType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'BirthDate',
                ]
                ]
            )
            ->add(
                'birthLocation',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'BirthLocation',
                ]
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Gender',
                ]
                ]
            )
            ->add(
                'phoneNumber',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Gender',
                ]
                ]
            )
            ->add(
                'profession',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Gender',
                ]
                ]
            )
            ->add(
                'nationality',
                TextType::class,
                [
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Gender',
                ]
                ]
            )
            ->add('address', AddressType::class);
    }

    /**
     * @param OptionsResolver $resolver Get form type OptionsResolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => 'UserBundle\Entity\UserPersonalInfos'
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'userbundle_userpersonalinfos';
    }
}
