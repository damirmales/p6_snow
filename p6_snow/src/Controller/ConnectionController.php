<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnectionController extends AbstractController
{

    /**
     * Inform about errors if any entered in the connection form fields
     * @Route("/login", name="connection_user")
     *
     * @param AuthenticationUtils $utils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername(); // dernière valeur entrée dans le champ _username du formulaire de login

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
