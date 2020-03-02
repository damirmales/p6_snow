<?php

namespace App\Form;

use App\Entity\Figure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateFigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('slug')
            //  ->add('featureImage')
            ->add('description')
            ->add('figGroup', ChoiceType::class, [
                'choices' => [
                    'Flip' => 'Flip',
                    'Slide' => 'Slide',
                    'Grab' => 'Grab',
                    'Rotation' => 'Rotation',
                ]])
            ->add('image', FileType::class, [
                'label' => 'Image de présentation (jpeg file)',
                'attr' => ['placeholder' => 'Télécharger une image locale'],
                'mapped' => false,
                'required' => false])/*
            ->add('CreateDate')
               ->add('updateDate')
            ->add('editor')
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}
