<?php

namespace App\Controller\Api;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="app_api_genre_getAll", methods={"GET"})
     */
    public function getAll(GenreRepository $genreRepository): JsonResponse
    {
        $genres = $genreRepository->findAll();

        return $this->json($genres,Response::HTTP_OK,[],["groups" => "genres"]);
    }
}
