<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApi{
    
    private $apiKey;
    private $client;

    public function __construct(string $apiKey, HttpClientInterface $client)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    /**
     * Fetch a movie in the omdbApi database by his title
     * @param string $title title of a movie to find in the api
     * 
     * @return array with data of the movie
     */
    public function fetchByTitle(string $title) :array {

        $response = $this->client->request(
            'GET',
            'http://www.omdbapi.com/',
            [
                "query" => [
                    "apikey" => $this->apiKey,
                    "t" => $title
                ]
            ]
        );

        // Permet de récupérer le contenu de la réponse (en général un json)
        // $result = $response->getContent();
        
        // Response to array convertis le json en tableau
        $result = $response->toArray();

        return $result;
    }

    /**
     * get an image of the movie search by title
     * 
     * @param string $title title of a movie to find in the api
     * 
     * @return string|null poster of the movie or null if movie doesnt exist
     */
    public function fetchImageByTitle(string $title) :?string{
        $movie = $this->fetchByTitle($title);
        
        if(!array_key_exists("Poster",$movie)){
            return null;
        }

        return $movie["Poster"];

    }
}