<?php

namespace App\Controller\Api;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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

    /**
     * @Route("/api/movies/{id}", name="app_api_movie_getOneById", methods={"GET"}, requirements={"id"="\d+"})
     * @Route("/api/movies/{slug}", name="app_api_movie_getOneBySlug", methods={"GET"})
     */
    public function getOne(Movie $movie): Response
    {

        // Renvoi un json avec en premier argument les données et en deuxième un status code
        return $this->json($movie,Response::HTTP_OK,[],["groups" => "movies"]);
    }

    /**
     * @Route("/api/movies/genres/{id}", name="app_api_movie_getAllByGenre", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function getAllByGenre(?Genre $genre): Response
    {

        // Vu que genre est nullable, si l'id ne permet pas l'autowire car genre inexistant, je gère l'erreur à la main en retournant un json d'erreur
        if(!$genre){
            return $this->json(["error" => "Genre non trouvé"],Response::HTTP_NOT_FOUND);
        }
        
        $movies = $genre->getMovies();
        
        // Renvoi un json avec en premier argument les données et en deuxième un status code
        return $this->json($movies,Response::HTTP_OK,[],["groups" => "movies"]);
    }

    /**
     * @Route("/api/movies/random", name="app_api_movie_getOneRandom", methods={"GET"})
     */
    public function getOneRandom(MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->findRandom();
        // TODO générer un objet symfony pour le film, car la requete custom nous renvoi un array avec les données sql brutes

        // Renvoi un json avec en premier argument les données et en deuxième un status code
        return $this->json($movie,Response::HTTP_OK,[]);
    }

    /**
     * @Route("/api/movies", name="app_api_movie_create", methods={"POST"})
     * @isGranted("ROLE_ADMIN", message="Vous devez être un administrateur")
     */
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, MovieRepository $movieRepository): Response
    {
        
        // Je recupère le json dans la requete
        $json = $request->getContent();

        // Si on peut sérializer avec $this->json, il est possible également d'importer le serializer et de faire la démarche en sens inverse et transformer un json en objet
        // Pensez à composer require symfony/serializer-pack
        try{
            // si le code ne lance pas d'expection nous n'allons pas dans le catch (json valide)
            $movie = $serializer->deserialize($json, Movie::class, 'json');

        }catch(NotEncodableValueException $e){

            return $this->json(["error" => "Json non valide"],Response::HTTP_BAD_REQUEST);
        }  
        // $movieRepository->add($movie,true);

        // J'utilise le composant validator pour vérifier si les champs sont bien remplis
        // Si l'objet est incomplet, j'aurai une erreur sql en faisant le add
        $errors = $validator->validate($movie);
        
        // Je boucle sur le tableau d'erreur
        // cette condition correspond à si il y a une erreur
        if(count($errors) > 0){
            // Je créer un tableau avec mes erreurs
            $errorsArray = [];
            foreach($errors as $error){
                // A l'index qui correspond au champs mal remplis, j'y injecte le/les messages d'erreurs
                $errorsArray[$error->getPropertyPath()][] = $error->getMessage();
            }
            return $this->json($errorsArray,Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        // TODO AJOUTER LE FILM EN BDD
        $movieRepository->add($movie,true);
        

        // Renvoi un json avec en premier argument les données et en deuxième un status code
        return $this->json(
            $movie,
            Response::HTTP_CREATED,
            [
                "Location" => $this->generateUrl("app_api_movie_getOneById", ["id" => $movie->getId()])
            ],
            [
                "groups" => "movies"
            ]
        );
    }
}


