<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            $newUser->setRole('member');
            $newUser->setStatus(false);
            $newUser->setToken('a3753573543a');

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
