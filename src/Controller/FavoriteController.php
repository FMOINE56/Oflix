<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    /**
     * @Route("/favoris", name="app_favorite_list")
     */
    public function list(): Response
    {
        return $this->render('favorite/list.html.twig');
    }
}
