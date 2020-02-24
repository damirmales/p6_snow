<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnectionController extends AbstractController
{

    /**
     * @Route("/login", name="connection_user")
     * @param AuthenticationUtils $utils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('connection/login.html.twig', [
            'errorMessage' => $error !== null,
            'username' => $username,
        ]);
    }


    /**
     * @Route("/logout", name="deconnection_user")
     */
    public function logout() // gérée dans security.yaml
    {

    }
}