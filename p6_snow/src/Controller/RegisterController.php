<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\IsFalse;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register_user")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $newUser = new User();

        $form = $this->createForm(RegisterType::class, $newUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser->setRole('ROLE_USER');
            $newUser->setStatus(false);
            $newUser->setToken('a3753573543a');

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
                }

                // updates the 'picture' field property to store the jpeg file name
                // instead of its contents
                $newUser->setPicture($newFilename);
            }


            $this->addFlash('success', 'Enregistrement effectué');

            $pswd = $encoder->encodePassword($newUser, $newUser->getPassword());
            $newUser->setPassword($pswd);
            $manager->persist($newUser);
            $manager->flush();

            return $this->redirectToRoute('home');

        }
        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
