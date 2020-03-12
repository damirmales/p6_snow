<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Media;
use App\Form\MediaType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{

    /**
     * @Route("/media/create/{slug}", name="create_media")
     */
    public function createMedia(Figure $figure, Request $request, EntityManagerInterface $entityManager)
    {
        $media = new Media();

        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $media->setType('photo');
            $media->setCreateDate(new DateTime('now'));
            $media->setFigure($figure);


            //-------- Manage the field devoted to upload default picture ----------------
            $imageFile = $form->get('photo_figure')->getData(); //from MediaType Filetype

            if ($imageFile) {
                $imageFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $newFilename = $imageFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where pictures of figures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('figures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'picture' field property to store the jpeg file name
                // instead of its contents
                $media->setUrl($newFilename);
            }
            $entityManager->persist($media);
            $entityManager->flush();

            $this->addFlash("success", "Ajout de média réussi");
            return $this->redirectToRoute('home');
        }
        return $this->render('media/add_media.html.twig', [
            'form' => $form->createView(),
            'figTitle' => $figure->getTitle(),
        ]);
    }

    /**
     * @Route("/media/{id}", name="edit_media")
     *
     */
    public function update(Media $media, Request $request, EntityManagerInterface $entityManager)
    {

        $title = $media->getTitle();
        $slug = $media->getFigure()->getSlug(); //Get figure's slug to send it to redirectToRoute()

        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($media);
            $entityManager->flush();
            return $this->redirectToRoute('page_figure', ['slug' => $slug]);
        }

        return $this->render('update.html.twig', [
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
        $slug = $media->getFigure()->getSlug(); //Get figure's slug to send it to redirectToRoute()
        $entityManager->remove($media);
        $entityManager->flush();

        return $this->redirectToRoute('page_figure', ['slug' => $slug]);
    }
}
