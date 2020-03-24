<?php

namespace App\Controller;

use App\Entity\PasswordLost;
use App\Entity\User;
use App\Form\PasswordLostType;
use App\Form\RegisterType;
use App\Repository\PasswordLostRepository;
use App\Repository\UserRepository;
use App\Services\RandomGeneratedValues;
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

        $newPasswordEntity = new PasswordLost(); //create an entity which is not registered in the database

        $form = $this->createForm(PasswordLostType::class, $newPasswordEntity);

        $form->handleRequest($request);

        //get user's data with user's username
        // $userData = $form->get('username');
        $userData = $form->get('email');
        $userProvidedEmail = $userData->getViewData();
        $bodyEmailMessage = "Cliquez sur le lien pour accéder au formulaire de changement de mot de passe:";

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->findUserEmail($userProvidedEmail);

            if ($user !== null) {
                $userLastname = $user->getLastname();
                /*   // generate a token
                   $randomGenerator = new RandomGeneratedValues();
                   $randomGenerator->generateRandomString();
                   $token = $randomGenerator->getRandomValue();
   */
                // update token field in database

                $token = $user->getToken();
                $pathToEmailPage = 'emails/password_email.html.twig';

                $sendEmail->sendEmail($user->getEmail(), $token, $userLastname, $bodyEmailMessage, $pathToEmailPage);
                $this->addFlash('success', 'Un email de renouvellement de mot de passe vous a été envoyé');
            } else {
                $this->addFlash('warning', 'Cet email ne correspond pas à un utilisateur inscrit');
            }
            // return $this->redirectToRoute('reset_password');

        }
        return $this->render('password/lost_password.html.twig', [
            'form' => $form->createView(),

        ]);
    }


    /**
     * @param $userProvidedEmail
     * @return mixed
     */
    public function findUserEmail($userProvidedEmail)
    {

        $repo = $this->getDoctrine()->getRepository(User::class);

        /* $findEmail = $repo->findByUsername([
             'username' => $userProvidedEmail
         ]);
         */

        $findEmail = $repo->findOneBy([
            'email' => $userProvidedEmail
        ]);

        return $findEmail;
    }
}
