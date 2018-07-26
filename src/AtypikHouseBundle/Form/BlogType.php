<?php

namespace AtypikHouseBundle\Form;

use AtypikHouseBundle\Entity\Blog;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
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
                'title',
                TextType::class,
                [
                    'label' => 'Page name',
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
                ]
            )
            ->add(
                'image',
                MediaType::class,
                [
                    'label' => 'Picture cover',
                    'provider' => 'sonata.media.provider.image',
                    'required' => true,
                    'context'  => 'blog',
                ]
            )
            ->add(
                'createdAt',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'required' => false,
                    'label' => 'Created At',
                    'attr' => [
                        'class' => 'date-picker form-control',
                        'max' => date('Y-m-d'),
                    ],
                    'html5' => false,
                    'format' =>'dd/MM/yyyy',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                'label' => 'Description (max : 255 character)',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 255,
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
            'data_class' => Blog::class,
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
        return 'atypikhousebundle_article';
    }
}
