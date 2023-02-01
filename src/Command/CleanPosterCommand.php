<?php

namespace App\Command;

use App\Entity\Movie;
use App\Service\OmdbApi;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CleanPosterCommand extends Command
{
    protected static $defaultName = 'app:clean-poster';
    protected static $defaultDescription = 'Scann all poster and replace the broken one';

    private $client;
    private $omdbApi;
    private $entityManager;

    public function __construct(HttpClientInterface $client, OmdbApi $omdbApi, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->omdbApi = $omdbApi;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        // $this
        //     ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //     ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        // ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title("Démarrage du scan de vos images");

        $movies = $this->entityManager->getRepository(Movie::class)->findAll();

        $broken = 0;

        foreach($movies as $movie){

            try{
                $response = $this->client->request(
                    'GET',
                    $movie->getPoster()
                );
                
                $response->getContent();

            }catch(Exception $e){
                $broken ++;

                $io->text('Un lien cassé a été trouvé ...');
                $io->progressStart(100);

                $newPoster = $this->omdbApi->fetchImageByTitle($movie->getTitle());
                if($newPoster){
                    $movie->setPoster($newPoster);
                }else{
                    $movie->setPoster("https://previews.123rf.com/images/2nix/2nix1408/2nix140800096/30818268-404-fichier-d-erreur-non-trouv%C3%A9-sur-le-site-de-la-page.jpg");
                }
                $io->progressFinish();
                
                
            }
        }
        $this->entityManager->flush();
       
        if($broken > 0){
            $io->success("Les liens cassés de vos images ont été remplacés ! ($broken lien/s)");

        }else{
            $io->success("Aucun lien cassés n'est présent sur votre site");
        }

        return Command::SUCCESS;
    }
}
