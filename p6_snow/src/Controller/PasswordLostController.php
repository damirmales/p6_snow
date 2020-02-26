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

        $newUser = new PasswordLost(); //create an entity which is not registered in the database

        $form = $this->createForm(PasswordLostType::class, $newUser);

        $form->handleRequest($request);

        //get user's data with user's username
        $userData = $form->get('username');
        $username = $userData->getViewData();
        //$userLastName = $form->get('lastname');

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->findUserEmail($username);
    
            if ($user !== null) {
                $userLastname = $user->getLastname();
                $randomGenerator = new RandomGeneratedValues();
                $randomGenerator->generateRandomString();
                $randomValue = $randomGenerator->getRandomValue();

                $sendEmail->sendEmail($user->getEmail(), $randomValue, $userLastname);  //find user's email with given user's username
                $this->addFlash('success', 'Un email vous a été envoyé');
            } else {
                $this->addFlash('warning', 'Ce pseudo ne correspond pas à un utilisateur inscrit');
            }
            // return $this->redirectToRoute('reset_password');

        }
        return $this->render('password/lost_password.html.twig', [
            'form' => $form->createView(),

        ]);
    }


    /**
     * @param $username
     * @return mixed
     */
    public function findUserEmail($username)
    {

        $repo = $this->getDoctrine()->getRepository(User::class);

        /* $findEmail = $repo->findByUsername([
             'username' => $username
         ]);
         */

        $findEmail = $repo->findOneBy([
            'username' => $username
        ]);

        return $findEmail;
    }
}
