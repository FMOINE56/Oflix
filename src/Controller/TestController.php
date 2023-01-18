<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Season;
use App\Repository\MovieRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test/ajouter/film", name="app_test_add")
     */
    // on utilise l'autowiring pour récupérer doctrine
    public function add(ManagerRegistry $doctrine): Response
    {

        // On utilise la méthode getManager pour récupérer l'entity manager qui nous permettra de faire des actions en bdd
        $entityManager = $doctrine->getManager();

        // J'instancie un film à blanc
        $movie = new Movie();
        // Je le var_dump(pour voir)
        $movie->setTitle("Il faut sauver le soldat ryan");
        $movie->setDuration(150);
        $movie->setReleaseDate(new DateTime("1998-09-20"));
        $movie->setSynopsis("Un film ou tout le monde meurt, cool ...");
        $movie->setSummary("Le début d'une longue suite de films ou il faut sauver Matt Damon");
        $movie->setPoster("blabla.jpg");
        $movie->setRating(4.5);


        // Stipule à doctrine, que l'objet movie est bien présent et qu'on veut éventuellement le sauvegarder en bdd
        $entityManager->persist($movie);

        // Le flush execute rééelement la requête en bdd
        $entityManager->flush();
        

        return $this->redirectToRoute('app_main_home');
    }

     /**
     * @Route("/test/film", name="app_test_list")
     */
    // on utilise l'autowiring pour récupérer doctrine
    public function list(MovieRepository $movieRepository, ManagerRegistry $doctrine): Response
    {

        // Je récupère tous les films
        // exemple avec le movieRepository directement
        // $movies = $movieRepository->findAll();
        // exemple avec doctrine pour récupérer le repository et les films en une linge
        $movies = $doctrine->getRepository(Movie::class)->findAll();

        // foreach($movies as $movie){
        //     foreach($movie->getSeasons() as $season){
        //         // dump($season);
        //     }
        // }
        dd($movies);
        return $this->render('test/index.html.twig');
    }

    /**
     * @Route("/test/film/{title}", name="app_test_show")
     */
    
    public function show(Movie $movie): Response
    {

        return $this->render('test/show.html.twig',[
            "movie" => $movie
        ]);
    }

     /**
     * @Route("/test/modifier/film/{id}", name="app_test_update")
     */
    
    public function update(ManagerRegistry $doctrine, Movie $movie): Response
    {

        // entity manager à récupérer
        $entityManager = $doctrine->getManager();

        // On modifier l'objet que l'on a déjà grâce au paramConverter
        $movie->setTitle("Borat");

        // ! persist n'est pas necessaire pour une modification, on peu flush directemnt
        $entityManager->flush();

        return $this->redirectToRoute("app_test_list");
    }

      /**
     * @Route("/test/supprimer/film/{id}", name="app_test_delete")
     */
    
    public function delete(ManagerRegistry $doctrine, Movie $movie): Response
    {

        // entity manager à récupérer
        $entityManager = $doctrine->getManager();

        // On utilise l'entityManager pour remove (remove est l'inverse de persist)
        $entityManager->remove($movie);

        // ! persist n'est pas necessaire pour une modification, on peu flush directemnt
        $entityManager->flush();

        return $this->redirectToRoute("app_test_list");
    }

       /**
     * @Route("/test/saison/add/{id}", name="app_test_addSeason")
     */
    
    public function addSeason(ManagerRegistry $doctrine,Movie $movie): Response
    {

        $entityManager = $doctrine->getManager();

        $season = new Season();

        // Je crée ma saison et je la lie au film grâce au setter
        $season->setNumberEpisodes(15);
        $season->setNumberSeason(1);

        // En cas de relation many to many attention la méthode est add... et non set...
        $season->setMovie($movie);

        $entityManager->persist($season);
        $entityManager->flush();


        return $this->redirectToRoute("app_test_list");
    }
}
