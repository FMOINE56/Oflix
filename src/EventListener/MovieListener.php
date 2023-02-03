<?php

// src/EventListener/UserChangedNotifier.php
namespace App\EventListener;

use App\Entity\Movie;
use App\Service\MySlugger;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MovieListener
{
    private $slugger;

    public function __construct(MySlugger $slugger)
    {
        $this->slugger = $slugger;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function changeSlug(Movie $movie, LifecycleEventArgs $event): void
    {
        // Je modifie le slug de l'entité passé à la méthode en utilisant mon servie MySlugger
        $movie->setSlug($this->slugger->slugify($movie->getTitle()));
    }
}