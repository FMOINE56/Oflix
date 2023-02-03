<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $onMaintenance;

    public function __construct(bool $onMaintenance)
    {
        $this->onMaintenance = $onMaintenance;
    }
    public function onKernelResponse(ResponseEvent $event): void
    {
        if(!$this->onMaintenance){
            return;
        }
        // On récupère l'objet réponse
        $response = $event->getResponse();

        // on récupère l'html 
        $content = $response->getContent();
        // symfony par moment comme sur la gestion des erreurs par exemple, envoi des subRequest, ces subRequest vont faire que votre subscriber va se trigger plusieurs fois, on bloque ça grâce à la condition ci-dessous
        if(!$event->isMainRequest()){
            return;
        }
        // Le but de mon subscrbier est de modifier de l'affichage, dans le cas ou la requete est de type ajax ou fetch, l'affichage est son problème du coup on arrête là aussi (façon 1 de bloqué un appel api sur le listener)
        if($event->getRequest()->headers->get("X-Requested-With") === "fetch" || $event->getRequest()->headers->get("X-Requested-With") === "XMLHttpRequest"){
            return;
        }
        // Façon 2 si notre réponse est un json, on arrête là
        if($response->headers->get("Content-Type") === "application/json"){
            return;
        }
   
        // Je remplace le html de base de la réponse par mon nouveau html qui comprend celui de base avec un petit +, la div de maintenance
        $formattedContent = preg_replace("^\<\/nav\>^",'</nav><div class="alert alert-danger mt-3 alert-dismissible fade show">Maintenance prévue mardi 10 janvier à 17h00 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>',$content,1);

        // On set le nouveau contenu, comme ça l'objet reponse part comme prévu mais avec des informations supplémentaires
        $response->setContent($formattedContent);
        
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
