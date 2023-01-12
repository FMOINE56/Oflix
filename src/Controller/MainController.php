<?php

namespace App\Controller;

use App\Model\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main_home")
     */
    public function home(Movie $movieModel): Response
    {
        // Exemple d'autowiring, en mettant en typeHint mon movieModel, symfony c'est chargé de l'instancié automatiquement
        

        // J'utilise mon getter pour avoir les films
        $movies = $movieModel->getMovies();

        // render en premier argument prend le template à afficher
        // en deuxième argument il prend un tableau de 
        return $this->render('main/home.html.twig', [
            'movies' => $movies,
        ]);
    }
}
