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

    // ce qui est entre  {} est un paramètre d'url, il sera utilisable dans notre méthode
    // requirements permet de venir valider le paramètre de la route avec un regex
    // ça permet premièrement de controler sa validité
    // deuxième ça donne la possibilité d'avoir deux routes similiaire mais avec des types de param differents
    /**
     * @Route("/film-serie/{id}", name="app_movie_show",requirements={"id"="\d+"})
     */
    // J'ai définis un paramètre à ma route qui s'appelle id, il sera disponible dans la variable $id, à condition d'avoir bien remplis la fonction path dans le twig
    public function show(int $id): Response
    {

        // J'appelle le bon model (pensez au use)
        $movieModel = new Movie;

        // J'utilise mon getter pour avoir les films
        $movie = $movieModel->getMovieById($id);

        // Si on trouve pas un film
        // On renvoi une erreur 404 avec le code 404 
        if (!$movie) {
            throw $this->createNotFoundException('Le film ou la série n\'a pas été trouvé');
    
            // the above is just a shortcut for:
            // throw new NotFoundHttpException('The product does not exist');
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    // requirements={"id"="d+"}
}
