<?php

namespace App\Controller;

use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{

    /**
     * @Route("/user/update/profil", name="profil_update")
     */
    public function updateProfil(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);

        $form->handleRequest($request);
        $avatarFile = $form->get('avatar')->getData();
        if ($form->isSubmitted() && $form->isValid()) {

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
                $user->setPicture($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("success", "Votre profil a été modifié");

        }

        return $this->render('profil/profil.html.twig', [
            'form' => $form->createView()
        ]);

    }


}