<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FeatureImgType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image_presentation', FileType::class, [
                'label' => 'Image de présentation (jpeg file)',
                'attr' => ['placeholder' => 'Télécharger une image locale'],
                'mapped' => false,
                'required' => true]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'validation_groups' => false,
        ]);
    }
}
