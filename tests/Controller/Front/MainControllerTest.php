<?php

namespace App\Tests\Controller\Front;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testHomePageNotConnected(): void
    {
        // Je crée un client
        $client = static::createClient();
        // Je lance une requete
        $crawler = $client->request('GET', '/');

        // Je vérifie si la requête est bien 2.x.x
        $this->assertResponseIsSuccessful();
        // Je vérifie vu que je suis pas connecté qu'il y a bien le bouton connexion
        $this->assertSelectorTextContains("a.btn-danger","Connexion");
        // Idem mais pour vérifier que les  favoris sont bien innacccessible
        $this->assertSelectorTextNotContains(".navbar-nav","Ma liste");
        // Je demande au "robot" de cliquer sur connexion
        $client->clickLink("Connexion");
        // Je vérifie que j'arrive bien sur la page de login
        $this->assertSelectorTextContains("h1","Login");
        // $this->assertSelectorTextContains('h1', 'Hello World');
    }

    public function testHomePageConnected(): void
    {
        // Je crée un client
        $client = static::createClient();

        // On récupère un utilisateur
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(["email" => "admin@gmail.com"]);

        // On log dans notre test un utilisateur récupéré plus haut via le container de service, le repository
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains(".navbar-nav","Ma liste");

    }
}
