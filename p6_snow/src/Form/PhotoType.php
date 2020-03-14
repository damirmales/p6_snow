<?php

namespace App\Form;

use App\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            //  ->add('url')
            ->add('photo_load', FileType::class, [
                'label' => 'Photo en rapport avec la figure',
                'attr' => ['placeholder' => 'Télécharger une photo'],
                'mapped' => false,
                'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
            'allow_add' => true
        ]);
    }
}
