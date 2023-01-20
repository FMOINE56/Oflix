<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // ! Fixtures de MOVIE

        for ($i=0; $i < 10; $i++) { 
            # code...
            $movie = new Movie();
            
            $movie->setTitle("Film $i");
            $movie->setDuration("120");
            $movie->setReleaseDate(new DateTime());
            $movie->setSynopsis("lorem synopsis");
            $movie->setSummary("lorem summary");
            $movie->setPoster("photo.com");
            $movie->setRating(rand(0,5));
            $movie->SetType("film");
            
            $manager->persist($movie);
        }

        // ! PERSON


        for ($i=0; $i < 10; $i++) { 
            
            $person = new Person();

            // Avec le design pattern builder on peut factoriser la syntaxe des enchainements de setter pour un meme objet
            $person
                ->setFirstname("Jean")
                ->setLastname("Bon");

            $manager->persist($person);
        }

        // ! Genre 

        for ($i=0; $i < 10 ; $i++) { 
            $genre = new Genre();

            $genre->setName("movie");
            
            $manager->persist($genre);
        }
        



        // ! FLUSH
        $manager->flush();
    }
}
