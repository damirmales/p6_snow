<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\CreateFigureType;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{

    /**
     * Add a new figure
     * @Route("/figure/new", name="new_figure")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        /* $user = $this->getUser();
         $status = $user->getStatus();
         dump($status);*/

        $fig = new Figure();

        $formCreateFig = $this->createForm(CreateFigureType::class, $fig);
        $formCreateFig->handleRequest($request);

        //TODO: test if user's status == 1

        if ($formCreateFig->isSubmitted() && $formCreateFig->isValid()) {
            $fig->setCreateDate(new \DateTime('now'));
            //  $fig->setSlug("tttttt");

            $fig->setEditor($this->getUser()); // available because user is connected

            $entityManager->persist($fig);
            $entityManager->flush();

            $this->addFlash("success", "Création réussie");

            return $this->redirectToRoute('home');
        }

        return $this->render('figure/new_figure.html.twig', [
            'form' => $formCreateFig->createView(),
        ]);
    }

    /**
     * @Route("/figure/{slug}", name="figure")
     */
    public function show($slug, FigureRepository $figRepo)
    {
        $fig = $figRepo->findOneBySlug($slug);

        return $this->render('figure/figure.html.twig', [
            'numeroFigure' => rand(1, 10),
            'fig' => $fig
        ]);
    }


}
