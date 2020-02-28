<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\CreateFigureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     * @Route("/figure", name="figure")
     */
    public function show()
    {
        return $this->render('figure/figure.html.twig', [
            'numeroFigure' => rand(1, 10)
        ]);
    }

    /**
     * @Route("/figure/new", name="new_figure")
     */
    public function create()
    {
        $fig = new Figure();

        $fig->setCreateDate(new \DateTime('now'));

        $formCreateFig = $this->createForm(CreateFigureType::class, $fig);

        return $this->render('figure/new_figure.html.twig', [
            'form' => $formCreateFig->createView(),
        ]);
    }


}
