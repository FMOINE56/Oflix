<?php

namespace App\EventSubscriber;

use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment as Twig;

class RandomMovieSubscriber implements EventSubscriberInterface
{
    private $movieRepository;
    private $twig;

    public function __construct(MovieRepository $movieRepository, Twig $twig)
    {
        // Mes dépendances
        $this->movieRepository = $movieRepository;
        $this->twig = $twig;
        
    }
    public function onKernelController(ControllerEvent $event): void
    {
        
        // J'utilise ma requete custom pour récup un film au hasard
        $movie = $this->movieRepository->findRandom();
        // je rajoute le film en variable global a twig
        $this->twig->addGlobal("randomMovie",$movie);
        
    }

    public static function getSubscribedEvents(): array
    {
        // Les evenements auquels la classe est abonné, ici le kernel.controller
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
