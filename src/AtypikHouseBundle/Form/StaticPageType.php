<?php

namespace AtypikHouseBundle\Form;

use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AtypikHouseBundle\Entity\StaticPage;

class StaticPageType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
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
                    'label' => 'Page name',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ]
            )
            ->add(
                'enabled',
                CheckboxType::class,
                [
                    'label' => 'Visible',
                    'required' => false,
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Page Description',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ]
            )
            ->add('content', FroalaEditorType::class);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => StaticPage::class,
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
        return 'atypikhousebundle_staticpage';
    }
}
