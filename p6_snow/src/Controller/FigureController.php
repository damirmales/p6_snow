<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\CreateFigureType;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{

    /**
     * @Route("/figure/{slug}/edit", name="edit_figure")
     */
    public function editFigure(Figure $figure, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CreateFigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setUpdateDate(new \DateTime('now'));

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

        $formCreateFig = $this->createForm(CreateFigureType::class, $fig);
        $formCreateFig->handleRequest($request);


        if ($formCreateFig->isSubmitted() && $formCreateFig->isValid()) {
            $fig->setCreateDate(new \DateTime('now'));
            //  $fig->setSlug("tttttt");

            $fig->setEditor($this->getUser()); // available because user is connected
            $imageFile = $formCreateFig->get('image')->getData();

            if ($imageFile) {
                $imageFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $newFilename = $imageFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where avatars are stored
                try {
                    $imageFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'picture' field property to store the jpeg file name
                // instead of its contents
                $fig->setFeatureImage($newFilename);
            }


            $entityManager->persist($fig);
            $entityManager->flush();

            $this->addFlash("success", "Création réussie");

            return $this->redirectToRoute('figure', [
                'slug' => $fig->getSlug()
            ]);
        }

        return $this->render('figure/new_figure.html.twig', [
            'form' => $formCreateFig->createView(),
        ]);
    }


    /**
     * @Route("/figure/{slug}", name="figure")
     */
    public function show($slug, Figure $figure)
    {


        return $this->render('figure/figure.html.twig', [
            'numeroFigure' => rand(1, 10),
            'fig' => $figure
        ]);
    }


}
