<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\User;

class UserPersonalFormAdminType extends AbstractType
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
            ->add('personalInfos', UserPersoFormAdminType::class);
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
            'data_class' => User::class
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'userbundle_user';
    }
}
