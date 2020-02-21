<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, $this->getConfiguration("Prénom", "Votre prénom"))
            ->add('lastname', TextType::class, $this->getConfiguration("Nom", "Votre nom"))
            ->add('email', TextType::class, $this->getConfiguration("Email", "Votre email"))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo", "Votre photo"))
            /*  ->add('role')
              ->add('status')
              ->add('token') */
            ->add('username', TextType::class, $this->getConfiguration("Pseudo", "Votre pseudo"))
            ->add('password', PasswordType::class, $this->getConfiguration("Mot de passe", "Votre mot de passe"))
            ->add('repeatPassword', PasswordType::class, $this->getConfiguration("Répéter mot de passe", "Répéter mot de passe"));

    }

    private function getConfiguration($label, $attribut)
    {
        return ['label' => $label,
            'attr' => ['placeholder' => $attribut]
        ];

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
