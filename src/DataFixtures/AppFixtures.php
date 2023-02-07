<?php

namespace App\DataFixtures;

use App\DataFixtures\Providers\AppProvider;
use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Review;
use App\Entity\Season;
use App\Entity\User;
use App\Service\MySlugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    private $slugger;

    public function __construct(UserPasswordHasherInterface $passwordHasher, MySlugger $slugger)
    {
        $this->passwordHasher = $passwordHasher;
        $this->slugger = $slugger;

    }

    public function load(ObjectManager $manager): void
    {
        // Ici je crée mon faker français
        $faker = Faker\Factory::create("fr_FR");
        $faker->addProvider(new AppProvider());

        // Vu qu'on utilise un ORM, on va utiliser un populator.
        $populator = new \Faker\ORM\Doctrine\Populator($faker, $manager);

        // ! Fixtures de MOVIE

        // * Nos fixtures avant le faker
        // for ($i=0; $i < 10; $i++) { 
        //     # code...
        //     $movie = new Movie();

        //     $movie->setTitle("Film $i");
        //     $movie->setDuration("120");
        //     $movie->setReleaseDate(new DateTime());
        //     $movie->setSynopsis("lorem synopsis");
        //     $movie->setSummary("lorem summary");
        //     $movie->setPoster("photo.com");
        //     $movie->setRating(rand(0,5));
        //     $movie->SetType("film");

        //     $manager->persist($movie);
        // }

        // Movie::class pour faire référence à la classe et 5 pour le nom d'itération en bdd
        $populator->addEntity(
            Movie::class,
            5,
            [
                // En troisième argument de la méthode addEntity, il est possible de mettre un tableau avec les columns à formatter manuellement
                // Pour les formater manuellement il faut en valeur de tableau faire passer une fonction, vu que nos fonctions seront appeller uniquement ici, j'utilise des fonctions anonymes
                "duration" => function () use ($faker) {
                    return $faker->numberBetween(10, 240);
                },
                "poster" => function () use ($faker) {
                    return "https://picsum.photos/id/" . $faker->numberBetween(1, 200) . "/300/500";
                },
                "rating" => function () use ($faker) {
                    return $faker->randomFloat(1, 1, 5);
                },
                "type" => function () use ($faker) {
                    // ternaire pour choisir entre film et série
                    $type = rand(0, 1) ? "Série" : "Film";
                    // return $type;
                    return $faker->randomElement(["Série", "Film"]);
                },
                "title" => function () use ($faker) {
                    return $faker->unique()->movieTitle();
                }
            ]
        );


        // ! PERSON


        // for ($i=0; $i < 10; $i++) { 
            
        //     $person = new Person();

        //     // Avec le design pattern builder on peut factoriser la syntaxe des enchainements de setter pour un meme objet
        //     $person
        //         ->setFirstname("Jean")
        //         ->setLastname("Bon");

        //     $manager->persist($person);
        // }

        $populator->addEntity(Person::class,10);

        // ! Genre 

        // for ($i=0; $i < 10 ; $i++) { 
        //     $genre = new Genre();

        //     $genre->setName("movie");
            
        //     $manager->persist($genre);
        // }
        
        $populator->addEntity(Genre::class,10,[
            "name" => function() use ($faker){
                return $faker->unique()->movieGenre();
            }
        ]);

        // ! Casting
        $populator->addEntity(Casting::class,20,[
            "role" => function() use ($faker){
                return $faker->unique()->name();
            },
            "creditOrder" => function() use ($faker){
                return $faker->numberBetween(1,100);
            }
        ]);
        
        // ! Season
        $populator->addEntity(Season::class,20,[
            "numberEpisodes" => function() use ($faker){
                return $faker->numberBetween(5,25);
            },
            "NumberSeason" => function() use ($faker){
                return $faker->unique->numberBetween(1,20);
            }
        ]);

        // // !review
        // $populator->addEntity(Review::class,40,[
        //     "rating" => function() use ($faker){
        //         return $faker->numberBetween(1,5);
        //     },
        //     "reactions" => function() use ($faker){
        //         return $faker->randomElements(['Rire', 'Pleurer', 'Réfléchir', 'Dormir', 'Rêver'],3);
        //     }
        // ]);

        // !USER
        $userAdmin = new User();
        
        // Création d'un admin
        $userAdmin->setEmail("admin@gmail.com");
        $userAdmin->setPassword($this->passwordHasher->hashPassword($userAdmin, "admin"));
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($userAdmin);

        // Création d'un utilisateur classique
        $user = new User();

        $user->setEmail("user@gmail.com");
        $user->setPassword($this->passwordHasher->hashPassword($user,"user"));
        $manager->persist($user);

        $userManager = new User();

        $userManager->setEmail("manager@gmail.com");
        $userManager->setPassword($this->passwordHasher->hashPassword($user,"manager"));
        $userManager->setRoles(["ROLE_MANAGER"]);
        $manager->persist($userManager);





        // !Movie Genre 
        

        // Populator execute, correspond au persist (en gros)

        // Ici je récupère les objets qui ont été crée
        $insertedItems = $populator->execute();

        // Je crée un tableau avec mes films
        $movies = [];

        // Je mets mes films dans mon tableau de films, à l'aide de ma variable $insertedItems
        foreach($insertedItems["App\Entity\Movie"] as $movie){
            // J'appelle le constructeur car pour une raison très sombre il n'est pas appellé 
            $movie->__construct();
            $movies[] = $movie;
        }

        // Je boucle sur les genres et je rajoute à chaque genre un film aléatoire
        foreach($insertedItems["App\Entity\Genre"] as $genre){
            
            // J'appelle le constructeur car pour une raison très sombre il n'est pas appellé 
            $genre->__construct();

            // Je récupère un index du tableau aléatoire
            $randIndex = array_rand($movies);
            // Je rajoute ce film à un genre
            $genre->addMovie($movies[$randIndex]);


            // Version avec le faker
            // $randomMovie = $faker->randomElement($movies);
            // $genre->addMovie($randomMovie);
        }
        

        // ! FLUSH
        $manager->flush();
    }
}

