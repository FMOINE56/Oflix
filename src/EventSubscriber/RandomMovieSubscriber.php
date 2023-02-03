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
        
        // Premiere étape récupérer le controller si y'a bien un controller
        $controller = $event->getController();
        // Je vérifie si c'est un tableau (les erreurs ne renvoi pas de tableau) et je récupère l'index 0
        if(is_array($controller)){
            $controller = $controller[0];
        }

        // Je veux récupérer le fqcn
        $controllerName = get_class($controller);

        // Je veux vérifier si mon controller est bien dans App\Controller\Front
        if(!str_contains($controllerName,"App\Controller\Front")){
            return;
        }

      
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
