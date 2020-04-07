<?php

namespace App\Form;

use App\Entity\Figure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateFigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('figGroup', ChoiceType::class, [
                'choices' => [
                    'Flip' => 'Flip',
                    'Slide' => 'Slide',
                    'Grab' => 'Grab',
                    'Rotation' => 'Rotation',
                ]])
            ->add('image_base', FileType::class, [
                'label' => 'Image de présentation (jpeg, poids inférieur à 1Mo)',
                'attr' => ['placeholder' => 'Télécharger une image locale'],
                'mapped' => false,
                'required' => false])

            // on inclus le formulaire des photos
            ->add('photos', CollectionType::class,
                [
                    'entry_type' => PhotoType::class,
                    'allow_add' => true, // allow adding several Photo forms
                    'allow_delete' => true
                ])

            // on inclus le formulaire des videos
            ->add('videos', CollectionType::class,
                [
                    'entry_type' => VideoType::class,
                    'allow_add' => true, // allow adding several Video forms
                    'allow_delete' => true
                ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}
