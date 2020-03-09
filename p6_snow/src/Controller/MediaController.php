<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("/media/{id}", name="edit_media")
     */
    public function index(Media $media, EntityManagerInterface $entityManager)
    {
        // $media = new Media();
        $title = $media->getTitle();

        $form = $this->createForm(MediaType::class, $media);
        //$form->handleRequest($request);

        return $this->render('media/index.html.twig', [
            'media' => $media,
            'title' => $title,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/media/{id}/delete", name="delete_media")
     */
    public function delete(Media $media, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($media);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}
