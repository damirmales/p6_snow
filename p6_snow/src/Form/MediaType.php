<?php

namespace App\Form;

use App\Entity\Media;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['attr' => ['placeholder' => 'Titre du média']])
            ->add('url', UrlType::class,
                ['attr' => ['placeholder' => 'Url d\'une vidéo en rapport avec la figure']])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Photo' => 'Photo',
                    'Vidéo' => 'Vidéo',

                ]])
            ->add('photo_figure', FileType::class, [
                'label' => 'Photo en rapport avec la figure',
                'attr' => ['placeholder' => 'Télécharger une photo locale'],
                'mapped' => false,
                'required' => false])/*
            ->add('createDate')
            ->add('updateDate')
            ->add('figure')
             */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            'allow_add' => true
        ]);
    }
}
