<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register_user")
     */
    public function register()
    {
        return $this->render('register/register.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }
}
