<?php

namespace App\Controller\Front;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main_home")
     */
    public function home(MovieRepository $movieRepository): Response
    {

        $movies = $movieRepository->findAllOrderByReleaseDate();

        // render en premier argument prend le template à afficher
        // en deuxième argument il prend un tableau de 
        return $this->render('front/main/home.html.twig', [
            'movies' => $movies,
        ]);
    }
}
