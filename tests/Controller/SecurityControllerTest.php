<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        // Je go sur la page de login
        $crawler = $client->request('GET', '/login');
        // Je remplis le form
        $crawler = $client->submitForm("login",[
            "_username" => "admin@gmail.com",
            "_password" => "admin"
        ]);
        // Je vérifie la redirection
        $this->assertResponseRedirects("http://localhost/");


    }
}
