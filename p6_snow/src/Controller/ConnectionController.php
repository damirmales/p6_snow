<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnectionController extends AbstractController
{
    /**
     * @Route("/login", name="connection_user")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        dump($error);

        return $this->render('connection/login.html.twig', [

        ]);
    }


    /**
     * @Route("/logout", name="deconnection_user")
     */
    public function logout()
    {


    }
}
