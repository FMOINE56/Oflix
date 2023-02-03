<?php

namespace App\Controller\Api;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/api/movies", name="app_api_movie_getAll", methods={"GET"})
     */
    public function getAll(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        // Renvoi un json avec en premier argument les données et en deuxième un status code
        return $this->json($movies,Response::HTTP_OK,[],["groups" => "movies"]);
    }
}


