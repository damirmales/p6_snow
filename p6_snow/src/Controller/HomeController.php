<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(FigureRepository $figRepo)
    {
        $figs = $figRepo->findAll();
        return $this->render('home/home.html.twig', [
            'figs' => $figs
        ]);
    }
}
