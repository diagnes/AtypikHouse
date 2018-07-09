<?php

namespace HousingBundle\Form\Custom;

use Doctrine\DBAL\Types\IntegerType;
use HousingBundle\Form\HousingDetailValueType;
use Proxies\__CG__\UserBundle\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Form\AddressType;
use HousingBundle\Entity\Housing;

class HousingDynamicDetailType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('details', HousingDetailValueType::class, []);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Housing::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'housingbundle_housing';
    }


}
