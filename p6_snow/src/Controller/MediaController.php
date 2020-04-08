<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Photo;
use App\Entity\Video;
use App\Form\PhotoType;
use App\Form\VideoType;
use App\Services\ImageUploadHelper;
use App\Services\UnlinkFile;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{


    /**
     * @Route("/photo/add/{slug}", name="add_photo")
     * @IsGranted("ROLE_USER")
     */
    public function addPhoto(Figure $figure, Request $request, EntityManagerInterface $entityManager)
    {
        $photo = new Photo();

        $photoForm = $this->createForm(PhotoType::class, $photo);
        $photoForm->handleRequest($request);

        if ($photoForm->isSubmitted() && $photoForm->isValid()) {

            $photo->setCreatedDate(new DateTime('now'));
            $photo->setFigure($figure);

//TODO: factorisez l'upload des photos
            //-------- Manage the field devoted to upload default picture ----------------
            $imageFile = $photoForm->get('file')->getData(); //from PhotoType Filetype

            if ($imageFile) {
                $uploadHelper = new ImageUploadHelper();
                $newImageName = $uploadHelper->imageUploadTest($imageFile, $photo, 'setFilename');

                // Move the file to the directory where pictures of figures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('figures_directory'),
                        $newImageName
                    );
                } catch (FileException $e) {
                    return new Response("Une erreur a été détectée");
                }

            }
            $entityManager->persist($photo);
            $entityManager->flush();

            $this->addFlash("success", "Ajout de média réussi");
            return $this->redirectToRoute('page_figure', ['slug' => $figure->getSlug()]);
        }
        return $this->render('media/add_photo.html.twig', [
            'form' => $photoForm->createView(),
            'figTitle' => $figure->getTitle(),
        ]);
    }


    /**
     * @Route("/video/add/{slug}", name="add_video")
     * @IsGranted("ROLE_USER")
     */
    public function addVideo(Figure $figure, Request $request, EntityManagerInterface $entityManager)
    {
        $video = new Video();

        $videoForm = $this->createForm(VideoType::class, $video);
        $videoForm->handleRequest($request);

        if ($videoForm->isSubmitted() && $videoForm->isValid()) {

            $video->setCreatedDate(new DateTime('now'));
            $video->setFigure($figure);

            $entityManager->persist($video);
            $entityManager->flush();

            $this->addFlash("success", "Ajout de vidéo réussi");
            return $this->redirectToRoute('page_figure', ['slug' => $figure->getSlug()]);
        }

        return $this->render('media/add_video.html.twig', [
            'form' => $videoForm->createView(),
            'figTitle' => $figure->getTitle(),
        ]);
    }


    /**
     * @Route("/figure/{slug}/photo/{id}", name="edit_photo")
     * @IsGranted("ROLE_USER")
     *
     */
    public function updatePhoto(Photo $photo, Request $request, EntityManagerInterface $entityManager)
    {

        $title = $photo->getTitle();
        $slug = $photo->getFigure()->getSlug(); //Get figure's slug to send it to redirectToRoute()

        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //TODO: factorisez l'upload des photos
            //-------- Manage the field devoted to upload default picture ----------------
            $imageFile = $form->get('file')->getData(); //from PhotoType Filetype

            if ($imageFile) {
                $photoName = $photo->getFilename();
                $delPhoto = new UnlinkFile($photoName);
                $delPhoto->delFile();

                $uploadHelper = new ImageUploadHelper();
                $newImageName = $uploadHelper->imageUploadTest($imageFile, $photo, 'setFilename');

                // Move the file to the directory where pictures of figures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('figures_directory'),
                        $newImageName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    return new Response("Une erreur a été détectée");
                }


            }
            $entityManager->persist($photo);
            $entityManager->flush();

            $this->addFlash("success", "Mise àjour de la photo effectuée");

            return $this->redirectToRoute('page_figure', ['slug' => $slug]);
        }

        return $this->render('media/update_photo.html.twig', [
            'photo' => $photo,
            'filename' => $photo->getFilename(),
            'title' => $title,
            'slug' => $slug,
            'id' => $photo->getId(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/photo/{id}/delete", name="delete_photo")
     * @IsGranted("ROLE_USER")
     */
    public function deletePhoto(Photo $photo, EntityManagerInterface $entityManager)
    {
        $slug = $photo->getFigure()->getSlug(); //Get figure's slug to send it to redirectToRoute()

        $photoName = $photo->getFilename();
        $delPhoto = new UnlinkFile($photoName);
        $delPhoto->delFile();

        $entityManager->remove($photo);
        $entityManager->flush();

        return $this->redirectToRoute('page_figure', ['slug' => $slug]);
    }

    /**
     * @Route("/figure/{slug}/video/{id}", name="edit_video")
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_USER")
     */
    public function deleteVideo(Video $video, EntityManagerInterface $entityManager)
    {
        $slug = $video->getFigure()->getSlug(); //Get figure's slug to send it to redirectToRoute()
        $entityManager->remove($video);
        $entityManager->flush();

        return $this->redirectToRoute('page_figure', ['slug' => $slug]);
    }


}
