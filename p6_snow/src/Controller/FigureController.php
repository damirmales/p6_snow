<?php

namespace App\Controller;

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
}
