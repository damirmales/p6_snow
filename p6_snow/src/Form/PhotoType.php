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
            ->add('file', FileType::class, [
                'label' => 'Photo en rapport avec la figure',
                'attr' => ['placeholder' => 'Télécharger une photo'],
                //'mapped' => false,
                // make it optional so you don't have to re-upload the jpeg file
                // every time you edit the Photo details
                'required' => false, // contrainte au niveau front
                'empty_data' => null
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
            'allow_add' => true
        ]);
    }
}
