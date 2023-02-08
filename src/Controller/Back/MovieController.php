<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\Service\MySlugger;
use App\Service\SendMailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;


class MovieController extends AbstractController
{
    /**
     * @Route("/back-office", name="app_back_movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('back/movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/back-office/film/ajouter", name="app_back_movie_add", methods={"GET", "POST"})
     */
    public function add(Request $request, MovieRepository $movieRepository,SendMailService $sendMailService, MySlugger $slugger): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

     
        if ($form->isSubmitted() && $form->isValid()) {


            $movieRepository->add($movie, true);

            // ! Code avec le service sendMailService
            $context = [
                "movie" => $movie,
                "user" => $this->getUser()->getUserIdentifier()
            ];
            $sendMailService->send(
                "Le film : ".$movie->getTitle()." a été crée",
                "add_movie.html.twig",
                $context
            );

            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/back-office/film/{id}", name="app_back_movie_show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('back/movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/back-office/modifier/{id}", name="app_back_movie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Movie $movie, MovieRepository $movieRepository, MySlugger $slugger): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
      
        $form->handleRequest($request);

      
        if ($form->isSubmitted() && $form->isValid()) {
            
            $movieRepository->add($movie, true);

            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/back-office/supprimer/film/{id}", name="app_back_movie_delete", methods={"POST"})
     */
    public function delete(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $movieRepository->remove($movie, true);
        }

        return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
    }
}
