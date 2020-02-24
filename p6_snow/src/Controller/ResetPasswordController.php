<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reset/password", name="reset_password")
     */
    public function index()
    {

        $newPassword = new ResetPassword();
        $form = $this->createForm(ResetPasswordType::class, $newPassword);

        return $this->render('password/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
