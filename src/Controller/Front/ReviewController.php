<?php

namespace App\Controller\Front;
use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     * @Route("/commentaire/ajouter/{id}", name="app_review_add",requirements={"id"="\d+"})
     */
    public function add(Movie $movie, Request $request, ReviewRepository $reviewRepository): Response
    {

        // On crée une review vide
        $review = new Review();

        // On crée notre formulaire en le lien à l'objet vide, comme ça lors du traitmeent l'objet sera remplis
        $form = $this->createForm(ReviewType::class,$review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd("form validé");
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
           $form->getData();
           $review->setMovie($movie);
           

           $reviewRepository->add($review,true);

           $this->addFlash(
                "success",
                "Critique bien postée"
           );

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_movie_show', ["id" => $movie->getId(), "slug" => $movie->getSlug()]);
        }


        return $this->renderForm('front/review/form.html.twig',[
            "form" => $form
        ]);
    }
}
