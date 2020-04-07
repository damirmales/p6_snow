<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, $this->getConfiguration("Prénom", "Votre prénom"))
            ->add('lastname', TextType::class, $this->getConfiguration("Nom", "Votre nom"))
            ->add('email', TextType::class, $this->getConfiguration("Email", "Votre email"))

            // add a user's image as avatar
            ->add('avatar', FileType::class, [
                'label' => 'Avatar (jpeg, 80x80mm, poids inférieur à 200Ko)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'required' => false,

                'constraints' => [
                    new File([
                        'maxSize' => '1024k', 'maxSizeMessage' => "l'image doit être doit inférieure à 1Mo",
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => "Le type de format image n'est pas valide",
                    ])
                ],
            ])
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
            // "allow_extra_fields" => true,
        ]);
    }
}
