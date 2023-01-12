<?php

namespace App\Controller;

use App\Model\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/film-serie", name="app_movie_list")
     */
    public function list(): Response
    {

        // J'appelle le bon model (pensez au use)
        $movieModel = new Movie;

        // J'utilise mon getter pour avoir les films
        $movies = $movieModel->getMovies();

        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/film-serie/{id}", name="app_movie_show")
     */
    // J'ai définis un paramètre à ma route qui s'appelle id, il sera disponible dans la variable $id, à condition d'avoir bien remplis la fonction path dans le twig
    public function show(int $id): Response
    {

        // J'appelle le bon model (pensez au use)
        $movieModel = new Movie;

        // J'utilise mon getter pour avoir les films
        $movie = $movieModel->getMovieById($id);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }
}
