<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Media;
use App\Entity\Photo;
use App\Entity\Video;
use App\Form\MediaType;
use App\Form\PhotoType;
use App\Form\VideoType;
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

        return $this->render('media/update.html.twig', [
            'media' => $media,
            'title' => $title,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/figure/{slug}/photo/{id}", name="edit_photo")
     *
     */
    public function updatePhoto(Photo $photo, Request $request, EntityManagerInterface $entityManager)
    {

        $title = $photo->getTitle();
        $slug = $photo->getFigure()->getSlug(); //Get figure's slug to send it to redirectToRoute()

        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($photo);
            $entityManager->flush();
            return $this->redirectToRoute('page_figure', ['slug' => $slug]);
        }

        return $this->render('media/update_photo.html.twig', [
            'photo' => $photo,
            'title' => $title,
            'slug' => $slug,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/photo/{id}/delete", name="delete_photo")
     */
    public function deletePhoto(Photo $photo, EntityManagerInterface $entityManager)
    {
        $slug = $photo->getFigure()->getSlug(); //Get figure's slug to send it to redirectToRoute()
        $entityManager->remove($photo);
        $entityManager->flush();

        return $this->redirectToRoute('page_figure', ['slug' => $slug]);
    }

    /**
     * @Route("/figure/{slug}/video/{id}", name="edit_video")
     *
     */
    public function updateVideo(Video $video, Request $request, EntityManagerInterface $entityManager)
    {

        $title = $video->getTitle();
        $slug = $video->getFigure()->getSlug(); //Get figure's slug to send it to redirectToRoute()

        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($video);
            $entityManager->flush();
            return $this->redirectToRoute('page_figure', ['slug' => $slug]);
        }

        return $this->render('media/update_video.html.twig', [
            'video' => $video,
            'title' => $title,
            'slug' => $slug,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/video/{id}/delete", name="delete_video")
     */
    public function deleteVideo(Video $video, EntityManagerInterface $entityManager)
    {
        $slug = $video->getFigure()->getSlug(); //Get figure's slug to send it to redirectToRoute()
        $entityManager->remove($video);
        $entityManager->flush();

        return $this->redirectToRoute('page_figure', ['slug' => $slug]);
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