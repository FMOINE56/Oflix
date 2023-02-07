<?php

// src/EventListener/UserChangedNotifier.php
namespace App\EventListener;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ReviewListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function CalculMovieRating(Review $review, LifecycleEventArgs $event): void
    {

        // Je récupère le flilm de la review
        $movie = $review->getMovie();
        $reviews = $movie->getReviews();
            
            // J'initialise une variable allNotes à 0
            $allNotes = null;

            // Je foreach sur les reviews et j'additione toutes les notes dans allNotes
            foreach($reviews as $review){
                $allNotes += $review->getRating();
            }

            // Je divise le total des notes par le nombre de note
            $rating = $allNotes / count($reviews);

            // Je set la note du film par mon résultat
            $movie->setRating(round($rating,1));

            // Je flush
            $this->entityManager->flush();

    }
}