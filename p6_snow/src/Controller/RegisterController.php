<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register_user")
     */
    public function register()
    {
        $newUser = new User();

        $form = $this->createForm(RegisterType::class, $newUser);

        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
