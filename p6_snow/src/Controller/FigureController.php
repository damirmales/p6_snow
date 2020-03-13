<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\Media;
use App\Entity\Photo;
use App\Entity\Video;
use App\Form\CommentType;
use App\Form\CreateFigureType;
use App\Form\FeatureImgType;
use App\Repository\CommentRepository;
use App\Repository\MediaRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class FigureController extends AbstractController
{

    /**
     * @Route("/figure/{slug}/featureImage", name="image_presentation")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editFeatureimage(Figure $figure)
    {
        $form = $this->createForm(FeatureImgType::class, $figure);
        return $this->render('figure/edit_feature_image.html.twig', [

                'form' => $form->createView(),
            ]
        );

    }

    /**
     * @Route("/figure/{slug}/featureImage/remove", name="delete_feature_image")
     */
    public function deleteFeatureImage(Figure $figure, EntityManagerInterface $entityManager)
    {
        $figure->setFeatureImage('');
        $entityManager->persist($figure);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/figure/{slug}/edit", name="edit_figure")
     * @IsGranted({"ROLE_USER", "ROLE_ADMIN"})
     *
     */
    public function editFigure(Figure $figure, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CreateFigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setUpdateDate(new DateTime('now'));
            //-------------------------------------------------------

            $figure->setEditor($this->getUser()); // available because user is connected
            $imageFile = $form->get('image_base')->getData();

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
                $figure->setFeatureImage($newFilename);
            }
            // let added media to persist before insert it to the database
            foreach ($figure->getMedia() as $medium) {
                $medium->setCreateDate(new DateTime('now'));

                $medium->setFigure($figure);
                $entityManager->persist($medium);
            }

            $entityManager->persist($figure);
            $entityManager->flush();


            $this->addFlash("success", "Modification réussie");

            return $this->redirectToRoute('page_figure', [
                'slug' => $figure->getSlug(),


            ]);
        }

        return $this->render('figure/edit.html.twig', [
            'form' => $form->createView(),
            'fig' => $figure,


        ]);
    }


    /**
     * Add a new figure
     * @Route("/figure/new", name="new_figure")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {

        $fig = new Figure();
        $photo = new  Photo();
        $video = new Video();

        $fig->addPhoto($photo);// link Photo entity to Figure
        $fig->addVideo($video);// link Video entity to Figure

        $formCreateFig = $this->createForm(CreateFigureType::class, $fig);
        $formCreateFig->handleRequest($request);

        if ($formCreateFig->isSubmitted() && $formCreateFig->isValid()) {
            $fig->setCreateDate(new DateTime('now'));
            $fig->setEditor($this->getUser()); // available because user is connected

            dump($formCreateFig);
            // die();

            //-------- Manage the field devoted to upload default picture ----------------
            $imageFile = $formCreateFig->get('image_base')->getData(); //from CreateFigureType Filetype

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
                $fig->setFeatureImage($newFilename);
            }
            /*-
                        //-------- Manage the field devoted to upload extra figure pictures ----------------
                        $mediumFile = $formCreateFig->get($media)->get('photo_figure')->getData(); //from CreateFigureType Filetype

                        if ($mediumFile) {
                            $imageFilename = pathinfo($mediumFile->getClientOriginalName(), PATHINFO_FILENAME);

                            $newFilename = $imageFilename . '-' . uniqid() . '.' . $mediumFile->guessExtension();

                            // Move the file to the directory where pictures of figures are stored
                            try {
                                $mediumFile->move(
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
 */
            // let added photo to persist before insert it to the database
            foreach ($fig->getPhotos() as $photo) {
                $photo->setCreatedDate(new DateTime('now'));
                $photo->setFigure($fig);
                $entityManager->persist($photo);
            }

            // let added video to persist before insert it to the database
            foreach ($fig->getVideos() as $video) {
                $video->setCreatedDate(new DateTime('now'));
      
                $video->setFigure($fig);
                $entityManager->persist($video);
            }

            $entityManager->persist($fig);
            $entityManager->flush();


            $this->addFlash("success", "Création de figure réussie");

            return $this->redirectToRoute('page_figure', [
                'slug' => $fig->getSlug()
            ]);
        }

        return $this->render('figure/new_figure.html.twig', [
            'form' => $formCreateFig->createView(),
        ]);
    }

    /**
     * @Route("/figure/{slug}/delete", name="delete_figure")
     */
    public function delete(Figure $figure, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($figure);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }


    /**
     *
     * @Route("/figure/{slug}/{page}", name="page_figure" )
     *
     */
    public function show($page = 1, Request $request, Figure $figure, EntityManagerInterface $entityManager,
                         CommentRepository $commentRepository,
                         MediaRepository $mediaRepository)
    {

        $comment = new Comment();
        $numPage = $page;


        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser())
                ->setFigure($figure)
                ->setCreateDate(new DateTime());

            $entityManager->persist($comment);
            $entityManager->flush();
        }
        //------------------------- Pagination Pictures gallery -----------------------------
        $numPictPage = 1;
        $paginationPictLimit = 3;
        $paginationPictOffset = $numPictPage * $paginationPictLimit - $paginationPictLimit;
        $numberOfPictPerpage = 3;
        $totalPict = count($mediaRepository->findByFigure([
            'figure' => $figure,
        ]));
        $rangeOfPictures = ceil($totalPict / $numberOfPictPerpage);


//------------------------- pagination Comments-----------------------------
        $paginationLimit = 2;
        $paginationOffset = $numPage * $paginationLimit - $paginationLimit;
        $numberOfCommentPerpage = 2;
        $totalComments = count($commentRepository->findByFigure([
            'figure' => $figure,

        ]));

        $rangeOfComments = ceil($totalComments / $numberOfCommentPerpage);


//------------------------- -----------------------------

        return $this->render('figure/figure.html.twig', array(
            'comments' => $commentRepository->findByFigure(['figure' => $figure], array('createDate' => 'DESC'), $paginationLimit, $paginationOffset),
            'medias' => $mediaRepository->findByFigure(['figure' => $figure], array('createDate' => 'DESC'), $paginationLimit, $paginationPictOffset),

            'fig' => $figure,
            'form' => $form->createView(),

            'pagesOfComments' => $rangeOfComments,
            'numPage' => $numPage,

            'pagesOfPictures' => $rangeOfPictures,
            'numPictPage' => $numPictPage,
        ));
    }


}
