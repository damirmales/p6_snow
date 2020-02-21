<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordLostType;
use App\Form\RegisterType;
use App\Services\SendEmail;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PasswordLostController extends AbstractController
{
    /**
     * @Route("/lost_password", name="lost_password")
     * @param SendEmail $sendEmail
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function lostPassword(Request $request, SendEmail $sendEmail, \Swift_Mailer $mailer)
    {

        $newUser = new User();

        $form = $this->createForm(PasswordLostType::class, $newUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sendEmail->sendEmail();

        }
        return $this->render('password/lost_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
