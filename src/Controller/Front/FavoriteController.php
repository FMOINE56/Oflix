<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    /**
     * @Route("/favoris", name="app_favorite_list")
     */
    // Ci dessous un exemple d'autowiring avec le type-hint
    public function list(RequestStack $requestStack): Response
    {

        // grâce à requestStack, on peut utiliser la methode getSession pour récuperer un objet qui nous permettra de manipuler les sessions
        $session = $requestStack->getSession();

        // Je vais piocher mes favoris dans le tableau de favoris des sessions
        $favorites = $session->get("favorites");
        // dd($favorites);
        // $session->clear();
        // dd($favorites);
        return $this->render('front/favorite/list.html.twig',[
            "favorites" => $favorites
        ]);
    }

     /**
     * @Route("/favoris/ajouter/{id}", name="app_favorite_add",requirements={"id"="\d+"})
     */
    public function add(RequestStack $requestStack,Movie $movie): Response
    {

        
        // grâce à requestStack, on peut utiliser la methode getSession pour récuperer un objet qui nous permettra de manipuler les sessions
        $session = $requestStack->getSession();


        // Je vais piocher mes favoris dans le tableau de favoris des sessions
        $favorites = $session->get("favorites", []);

        // on a les sessions 
            // à l'attribut favoris on a un tableau
                // dans ce tableau on a les index des films en favoris, avec pour valeur les objets des films

        // Je rajoute dans le tableau à l'index de l'id du film, le film, cette ligne permet à la fois de retrouver facilement un film dans les favoris par son id et d'éviter les doublons
        $favorites[$movie->getId()] = $movie;

        // Une fois la session modifié comme je le souhaite je peux la remettre à sa place
        $session->set("favorites", $favorites);

        // Ceci est un exemple de flash message
        $this->addFlash(
            'success',
            $movie->getTitle().' a bien été ajouté aux favoris'
        );
        

        return $this->redirectToRoute('app_favorite_list');

    }


     /**
     * @Route("/favoris/supprimer/{id}", name="app_favorite_remove",requirements={"id"="\d+"})
     */
    // Ci dessous un exemple d'autowiring avec le type-hint
    public function remove(RequestStack $requestStack, int $id): Response
    {

        // grâce à requestStack, on peut utiliser la methode getSession pour récuperer un objet qui nous permettra de manipuler les sessions
        $session = $requestStack->getSession();

        // Je vais piocher mes favoris dans le tableau de favoris des sessions
        $favorites = $session->get("favorites");

        // Je check si le film voulant être supprimé existe
        if(array_key_exists($id,$favorites)){
            // si le film est bien dans les favoris, je supprime
            unset($favorites[$id]);
        }else{
            // Ceci est un exemple de flash message
            $this->addFlash(
                'danger',
                "Le film n'est pas présent dans les favoris"
            );
        }

        // Je mets mon tableau de favoris dans les favoris avec la valeur en moins que j'ai supprimé
        $session->set("favorites", $favorites);

        $this->addFlash(
            'warning',
            "Le film a bien été enlevé des favoris"
        );

        // Je redirige
        return $this->redirectToRoute('app_favorite_list');
    }

        /**
     * @Route("/favoris/vider", name="app_favorite_empty")
     */
    // Ci dessous un exemple d'autowiring avec le type-hint
    public function empty(RequestStack $requestStack): Response
    {

        $session = $requestStack->getSession();

        // Je supprime tout les favoris
        $session->remove("favorites");

   
        // Je redirige
        return $this->redirectToRoute('app_favorite_list');
    }
}


