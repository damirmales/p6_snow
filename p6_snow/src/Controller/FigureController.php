<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\Media;
use App\Form\CommentType;
use App\Form\CreateFigureType;
use App\Repository\CommentRepository;
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

            return $this->redirectToRoute('figure', [
                'slug' => $figure->getSlug()
            ]);
        }

        return $this->render('figure/edit.html.twig', [
            'form' => $form->createView(),
            'title' => $figure->getTitle()
        ]);
    }


    /**
     * Add a new figure
     * @Route("/figure/new", name="new_figure")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {

        $fig = new Figure();
        $media = new  Media();
        $fig->addMedium($media);// link Media entity to Figure

        $formCreateFig = $this->createForm(CreateFigureType::class, $fig);
        $formCreateFig->handleRequest($request);

        if ($formCreateFig->isSubmitted() && $formCreateFig->isValid()) {
            $fig->setCreateDate(new DateTime('now'));


            $fig->setEditor($this->getUser()); // available because user is connected
            $imageFile = $formCreateFig->get('image_base')->getData();

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
            // let added media to persist before insert it to the database
            foreach ($fig->getMedia() as $medium) {
                $medium->setCreateDate(new DateTime('now'));

                $medium->setFigure($fig);
                $entityManager->persist($medium);
            }

            $entityManager->persist($fig);
            $entityManager->flush();

            $this->addFlash("success", "Création de figure réussie");

            return $this->redirectToRoute('figure', [
                'slug' => $fig->getSlug()
            ]);
        }

        return $this->render('figure/new_figure.html.twig', [
            'form' => $formCreateFig->createView(),
        ]);
    }


    /**
     *
     * @Route("/figure/{slug}/{page}", name="page_figure" )
     *
     */
    public function show($page = 1, Request $request, Figure $figure, EntityManagerInterface $entityManager, CommentRepository $commentRepository)
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
//------------------------- pagination -----------------------------
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
            'fig' => $figure,
            'form' => $form->createView(),
            'pagesOfComments' => $rangeOfComments,
            'numPage' => $numPage,
        ));
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


}
