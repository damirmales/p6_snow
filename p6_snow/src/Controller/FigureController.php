<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\Photo;
use App\Entity\Video;
use App\Form\CommentType;
use App\Form\CreateFigureType;
use App\Form\EditFigureType;
use App\Form\FeatureImgType;
use App\Repository\CommentRepository;
use App\Repository\PhotoRepository;
use App\Repository\VideoRepository;
use App\Services\PaginationParam;
use App\Services\UnlinkFile;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FigureController extends AbstractController
{

    /**
     * @Route("/figure/{slug}/image-presentation", name="image_presentation")
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
            $this->addFlash("success", "Image de présentation modifiée");
            return $this->redirectToRoute('edit_figure', [
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
     * @Route("/figure/{slug}/image-presentation/suppression", name="delete_feature_image")
     */
    public function deleteFeatureImage(Figure $figure, EntityManagerInterface $entityManager)
    {
        $imageName = $figure->getFeatureImage();
        $delFeature = new UnlinkFile($imageName);
        $delFeature->delFile();


        $figure->setFeatureImage('figure_default.jpeg');
        $entityManager->persist($figure);
        $entityManager->flush();
        $this->addFlash("success", "Image personnalisée supprimée, ");
        return $this->redirectToRoute('edit_figure', [
            'slug' => $figure->getSlug(),
        ]);
    }


    /**
     * @Route("/figure/{slug}/edit", name="edit_figure")
     * @IsGranted({"ROLE_USER", "ROLE_ADMIN"})
     *
     */
    public function editFigure(Figure $figure, Request $request, EntityManagerInterface $entityManager,
                               PhotoRepository $photoRepository, VideoRepository $videoRepository)
    {
        $form = $this->createForm(EditFigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setUpdateDate(new DateTime('now'));
            //-------------------------------------------------------
            $figure->setEditor($this->getUser()); // available because user is connected


            $figure->defineSlug();
            $entityManager->persist($figure);
            $entityManager->flush();

            $this->addFlash("success", "Modification réussie");

            return $this->redirectToRoute('page_figure', [
                'slug' => $figure->getSlug(),
                'photos' => $figure->getPhotos(),
                'videos' => $figure->getVideos()
            ]);
        }

        return $this->render('figure/edit.html.twig', [
            'form' => $form->createView(),
            'fig' => $figure,
            'photos' => $photoRepository->findByFigure(['figure' => $figure], array('createdDate' => 'DESC')),
            'videos' => $videoRepository->findByFigure(['figure' => $figure], array('createdDate' => 'DESC')),
        ]);
    }


    /**
     * Add a new figure
     * @Route("/figure/new", name="new_figure")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $fig = new Figure();
        $formCreateFig = $this->createForm(CreateFigureType::class, $fig);
        $formCreateFig->handleRequest($request);

        if ($formCreateFig->isSubmitted() && $formCreateFig->isValid()) {
            $fig->setFeatureImage('figure_default.jpeg');
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
                $photoFilename = pathinfo($photo->getFile()->getClientOriginalName(), PATHINFO_FILENAME);
                $newPhotoFilename = $photoFilename . '-' . uniqid() . '.' . $photo->getFile()->guessExtension();

                // Move the file to the directory where pictures of figures are stored
                try {
                    $photo->getFile()->move(
                        $this->getParameter('figures_directory'),
                        $newPhotoFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    return new Response("Une erreur a été détectée");
                }
                $photo->setFilename($newPhotoFilename);

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
        $imageName = $figure->getFeatureImage();
        $delFeature = new UnlinkFile($imageName);
        $delFeature->delFile();

        $entityManager->remove($figure);
        $entityManager->flush();

        $this->addFlash("success", "Figure effacée");

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
        $commentPagination = new PaginationParam($page, 3, 3);
        $picturePagination = new PaginationParam(1, 10, 6);
        $videoPagination = new PaginationParam(1, 10, 6);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser())
                ->setFigure($figure)
                ->setCreateDate(new DateTime());

            $entityManager->persist($comment);
            $entityManager->flush();

            //clean comment's content field after submission
            unset($comment);
            unset($form);
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);

            $this->addFlash("comment", "Commentaire soumis");

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
