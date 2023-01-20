<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/film-serie", name="app_movie_list")
     */
    public function list(MovieRepository $movieRepository): Response
    {

        // Je récupère tous les films à l'aide du repository
        $movies = $movieRepository->findAllOrderByTitle();

        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
        ]);
    }

    // ce qui est entre  {} est un paramètre d'url, il sera utilisable dans notre méthode
    // requirements permet de venir valider le paramètre de la route avec un regex
    // ça permet premièrement de controler sa validité
    // deuxième ça donne la possibilité d'avoir deux routes similiaire mais avec des types de param differents
    /**
     * @Route("/film-serie/{id}", name="app_movie_show",requirements={"id"="\d+"})
     */
    // J'ai définis un paramètre à ma route qui s'appelle id, il sera disponible dans la variable $id, à condition d'avoir bien remplis la fonction path dans le twig
    public function show(Movie $movie, CastingRepository $castingRepository): Response
    {

        $castingList = $castingRepository->findAllJoinedToPersonByMovie($movie);

        // Ici le film est récupéré en bdd automatiquement par symfo à l'aide de ce qu'on appelle le paramConverter
        // Le paramConverter va analyser le paramètre d'url, s'il correspond avec un attribut de la classe passé en paramètre de la fonction il va automatiquement faire un findBy(le param, ici l'id) de l'objet en bdd et l'instancier

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'castingList' => $castingList
        ]);
    }

}
