<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;

use App\Repository\UserRepository;
use App\Services\SendEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register_user")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, SendEmail $sendEmail)
    {
        $newUser = new User();

        $form = $this->createForm(RegisterType::class, $newUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $token_for_email = $request->get('_token');
            if ($this->isCsrfTokenValid('token-email', $token_for_email)) {

                $newUser->setRole('ROLE_WAIT');
                $newUser->setStatus(false);
                $newUser->setToken($token_for_email);

                $bodyEmailMessage = "cliquez sur le lien pour valider votre inscription";
                $pathToEmailPage = 'emails/register_email.html.twig';
                $avatarFile = $form->get('avatar')->getData();

                if ($avatarFile) {
                    $avatarFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    // $safeFilename = $transliterator->transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $avatarFilename);
                    $newFilename = $avatarFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

                    // Move the file to the directory where avatars are stored
                    try {
                        $avatarFile->move(
                            $this->getParameter('avatars_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        return new Response("Une erreur a été détectée");
                    }

                    // updates the 'picture' field property to store the jpeg file name
                    // instead of its contents
                    $newUser->setPicture($newFilename);
                } else {
                    $newUser->setPicture('default-avatar.png');
                }

                $pswd = $encoder->encodePassword($newUser, $newUser->getPassword());
                $newUser->setPassword($pswd);

                $sendEmail->sendEmail($newUser->getEmail(), $token_for_email, $newUser->getLastname(), $bodyEmailMessage, $pathToEmailPage);

                $manager->persist($newUser);
                $manager->flush();
                $this->addFlash('success', 'Un email vous a été envoyé');
                //return $this->redirectToRoute('home');
            }
        }
        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("/home", name="check_token")
     */
    public
    function checkToken(Request $request, UserRepository $userRepo, EntityManagerInterface $manager) // check token from user's email
    {
        $token_from_email = $request->query->get('user_token');

        $user = $manager->getRepository(User::class)->findOneBy(array('token' => $token_from_email));


        if ($user != null) {

            $user->setStatus(1);
            $user->setRole('ROLE_USER');
            $user->setToken(0);
            // Don't use persist() to perform an update
            $manager->flush();

            $this->addFlash('success', "Hourra vous êtes membre");


        } else {

            $this->addFlash('warning', "Votre enregistrement n'as pas été validé");

        }
        return $this->redirectToRoute('home');
    }


}
