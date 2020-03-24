<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;

use Doctrine\ORM\EntityManagerInterface;
use http\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reset-password", name="reset_password")
     */
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {

        $resetPassword = new ResetPassword();

        $form = $this->createForm(ResetPasswordType::class, $resetPassword);
        $form->handleRequest($request);

        // on récupère le token du lien inclus dans l'email
        $token = $request->get('user_token');
        // à partir du token on récupère une instance de l'entité User pour la passer à la méthode d'encodage encodePassword
        $user = $manager->getRepository(User::class)->findOneBy(array('token' => $token));


        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère le mot de passe inscrit dans le champ password du formulaire password/reset_password.html.twig
            $newPassword = $resetPassword->getPassword();
            // on crypte le mot de passe
            $cryptedPassword = $encoder->encodePassword($user, $newPassword);
            // on place le nouveau mot de passe dans le champ password de l'entité User
            $user->setPassword($cryptedPassword);
            // on lance l'EntityManager pour effectuer les modifications dans la base de données

            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Votre mot de passe à bien été modifié");
            dd($user);
            //return $this->redirectToRoute("home");

        }

        return $this->render('password/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
