<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ApiAccessDeniedSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {

        // Je récupère l'exception levé par l'application
        $exception = $event->getThrowable();

        // Si mon exception n'est pas en lien avec le controle d'accès, je ne fais rien en retournant tout de suite la méthode
        if (!$exception instanceof AccessDeniedHttpException) {
            return;
        }

        // on récupère la requete
        $request = $event->getRequest();

        // Je récupère la route
        $route = $request->get("_route");

        // Si api n'est pas trouvé dans la string $route, on return pour stopper la fonction
        if(!strpos($route,"api")){
            return;
        }
        
        // Je crée ma réponse http en json
        $response = new JsonResponse(["error" => "Vous n'avez pas les droits d'accès"],Response::HTTP_FORBIDDEN);
        // Je modifie la réponse avec l'erreur symfo par ma réponse personnalisé c'est à dire un status forbidden et mon json
        $event->setResponse($response);

    }

    public static function getSubscribedEvents(): array
    {

       

        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
