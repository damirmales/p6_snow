<?php

namespace App\Controller;

use App\Entity\PasswordLost;
use App\Entity\User;
use App\Form\PasswordLostType;
use App\Services\SendEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordLostController extends AbstractController
{
    /**
     * @Route("/lost_password", name="lost_password")
     * @param SendEmail $sendEmail
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function lostPassword(Request $request, SendEmail $sendEmail, EntityManagerInterface $entityManager)
    {

        $newPasswordEntity = new PasswordLost(); //create an entity which is not registered in the database

        $form = $this->createForm(PasswordLostType::class, $newPasswordEntity);

        $form->handleRequest($request);

        $userData = $form->get('email');
        $userProvidedEmail = $userData->getViewData();
        $bodyEmailMessage = "Cliquez sur le lien pour accèder au formulaire de changement de mot de passe:";

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->findUserEmail($userProvidedEmail);

            if ($user !== null) {
                $userLastname = $user->getLastname();

                // update token field in database
                $token_for_email = $request->get('_token');
                if ($this->isCsrfTokenValid('token-lost-pswd', $token_for_email)) {
                    $user->setToken($token_for_email);

                    $token = $user->getToken();

                    $pathToEmailPage = 'emails/password_email.html.twig';

                    $sendEmail->sendEmail($user->getEmail(), $token, $userLastname, $bodyEmailMessage, $pathToEmailPage);

                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Un email de renouvellement de mot de passe vous a été envoyé');
                } else {
                    return new Response('Token non valide.');
                }
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


        $findEmail = $repo->findOneBy([
            'email' => $userProvidedEmail
        ]);

        return $findEmail;
    }
}
