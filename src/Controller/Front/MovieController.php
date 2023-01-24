<?php

namespace App\Controller\Front;
use App\Entity\Movie;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/film-serie", name="app_movie_list")
     */
    public function list(MovieRepository $movieRepository, Request $request): Response
    {

        // Astuce pour orderby sans passé par une requête custom, je récupère tous les films trié par ordre alphabétique
        // $movies = $movieRepository->findBy([],["title" => "ASC"]);
        // dd($movies);

        // Je récupère tous les films à l'aide du repository
        $movies = $movieRepository->findAllOrderByTitleSearch($request->get("search"));

        return $this->render('front/movie/list.html.twig', [
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
    public function show(Movie $movie, CastingRepository $castingRepository, MovieRepository $movieRepository): Response
    {

        $castingList = $castingRepository->findAllJoinedToPersonByMovie($movie);

  

        // Ici le film est récupéré en bdd automatiquement par symfo à l'aide de ce qu'on appelle le paramConverter
        // Le paramConverter va analyser le paramètre d'url, s'il correspond avec un attribut de la classe passé en paramètre de la fonction il va automatiquement faire un findBy(le param, ici l'id) de l'objet en bdd et l'instancier

        return $this->render('front/movie/show.html.twig', [
            'movie' => $movie,
            'castingList' => $castingList
        ]);
    }

}
