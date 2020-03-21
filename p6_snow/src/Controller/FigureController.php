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
use App\Repository\FigureRepository;
use App\Repository\MediaRepository;
use App\Repository\PhotoRepository;
use App\Repository\VideoRepository;
use App\Services\PaginationParam;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FigureController extends AbstractController
{

    /**
     * @Route("/figure/{slug}/featureImage", name="image_presentation")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editFeatureimage(Figure $figure, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(FeatureImgType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $figure->setUpdateDate(new DateTime('now'));

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
                    return new Response("une erreur a été détectée");
                }

                // updates the 'picture' field property to store the jpeg file name
                // instead of its contents
                $figure->setFeatureImage($newFilename);
            }

            $entityManager->persist($figure);
            $entityManager->flush();

            $this->addFlash("success", "Photo d'entête de figure modifiée");

            return $this->redirectToRoute('page_figure', [
                'slug' => $figure->getSlug(),

            ]);


        }

        return $this->render('figure/edit_feature_image.html.twig', [

                'form' => $form->createView(),
                'title' => $figure->getTitle(),
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
                    return new Response("une erreur a été détectée");
                }

                // updates the 'picture' field property to store the jpeg file name
                // instead of its contents
                $figure->setFeatureImage($newFilename);
            }
            // let added photo to persist before insert it to the database
            foreach ($figure->getPhotos() as $photo) {
                $photo->setCreatedDate(new DateTime('now'));

                $photo->setFigure($figure);
                $entityManager->persist($photo);
            }

            // Persist video before insert it to the database
            foreach ($figure->getVideos() as $video) {
                $video->setCreatedDate(new DateTime('now'));
                $video->setFigure($figure);
                $entityManager->persist($video);
            }
            dd($figure->setFeatureImage($newFilename));
            $entityManager->persist($figure);
            $entityManager->flush();


            $this->addFlash("success", "Modification réussie");

            return $this->redirectToRoute('page_figure', [
                'slug' => $figure->getSlug(),
                'photos' => $figure->getPhotos(),
                'video' => $figure->getVideos()

            ]);
        }

        return $this->render('figure/edit.html.twig', [
            'form' => $form->createView(),
            'fig' => $figure,
            'photos' => $figure->getPhotos(),
            'video' => $figure->getVideos()


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


        $formCreateFig = $this->createForm(CreateFigureType::class, $fig);
        $formCreateFig->handleRequest($request);

        if ($formCreateFig->isSubmitted() && $formCreateFig->isValid()) {
            $fig->setCreateDate(new DateTime('now'));
            $fig->setEditor($this->getUser()); // available because user is connected


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
                    return new Response("une erreur a été détectée");
                }

                // updates the 'FeatureImage' field property to store the jpeg file name
                // instead of its contents
                $fig->setFeatureImage($newFilename);
            }

            //-------- Manage the field devoted to upload extra figure pictures ----------------
            $photoFile = $formCreateFig->getData()->getPhotos(); //from PhotoType Filetype

            // let added photo to persist before insert it to the database
            foreach ($photoFile as $photo) {
                $photo->setCreatedDate(new DateTime('now'));
                $photo->setFigure($fig); // combine photo to the figure
                $imageFilename = pathinfo($photo->getFile()->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $imageFilename . '-' . uniqid() . '.' . $photo->getFile()->guessExtension();

                // Move the file to the directory where pictures of figures are stored
                try {
                    $photo->getFile()->move(
                        $this->getParameter('figures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    return new Response("Une erreur a été détectée");
                }
                $photo->setFilename($newFilename);

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
    public function delete($slug, Figure $figure, PhotoRepository $photoRepository, FigureRepository $figureRepository, EntityManagerInterface $entityManager)
    {
        // dd($photoRepository->findBy(['figure' => $figure]));
        //dd($photoRepository->findBy(['figure' => $figure]));

        //$entityManager->remove($photo);

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
                         PhotoRepository $photoRepository,
                         VideoRepository $videoRepository)
    {

        $comment = new Comment();
        $commentPagination = new PaginationParam($page, 2, 2);
        $picturePagination = new PaginationParam(1, 10, 6);
        $videoPagination = new PaginationParam(1, 3, 3);

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
        $picturePageLimit = $picturePagination->getPageItemLimit();
        $picturePerPage = $picturePagination->getNumberOfItemPerPage();
        $pictureOffset = $picturePagination->paginationOffset();


        $totalPict = count($photoRepository->findByFigure([
            'figure' => $figure,
        ]));
        $rangeOfPictures = ceil($totalPict / $picturePerPage);

        //------------------------- Pagination videos gallery -----------------------------
        $videoPageLimit = $videoPagination->getPageItemLimit();
        $videoPerPage = $videoPagination->getNumberOfItemPerPage();
        $videoOffset = $videoPagination->paginationOffset();


        $totalVideos = count($photoRepository->findByFigure([
            'figure' => $figure,
        ]));
        $rangeOfVideo = ceil($totalVideos / $videoPerPage);


//------------------------- pagination Comments-----------------------------

        $pageLimit = $commentPagination->getPageItemLimit();
        $commentPerPage = $commentPagination->getNumberOfItemPerPage();
        $commentOffset = $commentPagination->paginationOffset();

        $totalComments = count($commentRepository->findByFigure([
            'figure' => $figure,
        ]));

        $rangeOfComments = ceil($totalComments / $commentPerPage);


        return $this->render('figure/figure.html.twig', array(
            'comments' => $commentRepository->findByFigure(['figure' => $figure], array('createDate' => 'DESC'), $pageLimit, $commentOffset),

            'photos' => $photoRepository->findByFigure(['figure' => $figure], array('createdDate' => 'DESC'), $picturePageLimit, $pictureOffset),
            'videos' => $videoRepository->findByFigure(['figure' => $figure], array('createdDate' => 'DESC'), $videoPageLimit, $videoOffset),

            'fig' => $figure,
            'form' => $form->createView(),

            'pagesOfComments' => $rangeOfComments,
            'numPage' => $commentPagination->getStartPageNumber(),

            'pagesOfPictures' => $rangeOfPictures,
            'numPictPage' => $picturePagination->getStartPageNumber(),

            'pagesOfvideos' => $rangeOfVideo,
            'numVideoPage' => $videoPagination->getStartPageNumber(),
        ));
    }


}
