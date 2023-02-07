<?php

namespace App\Tests\Service;

use App\Service\MySlugger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Slugger\AsciiSlugger;

class MySluggerTest extends TestCase
{
    // J'ai mis pleins de cas d'utilisations de mon slugger  pour les tester dans une boucles plus tard
    private const TEST_CASES = [
        [
            "input" => "Walking Deads",
            "expectedLower" => "walking-deads",
            "expectedUpper" => "Walking-Deads"
        ],
        [
            "input" => "Dune (2022)",
            "expectedLower" => "dune-2022",
            "expectedUpper" => "Dune-2022"
        ],
        [
            "input" => "H",
            "expectedLower" => "h",
            "expectedUpper" => "H"
        ],
    ];

    public function testSlugify(): void
    {
        // j'instancie l'imprémentation de la sluggerInterface
        $slugger = new AsciiSlugger("fr");

        // J'instancie mon mySlugger que je veux tester avec tous les params possibles
        $mySluggerLower = new MySlugger($slugger, true);
        $mySluggerUpper = new MySlugger($slugger, false);

        foreach(self::TEST_CASES as $testCase){
            // Je récupérer les slugs
            $sluggerLower = $mySluggerLower->slugify($testCase["input"]);
            $sluggerUpper = $mySluggerUpper->slugify($testCase["input"]);
    
            $this->assertEquals($testCase["expectedLower"],$sluggerLower, "Le slug ne correspond pas...");
            $this->assertEquals($testCase["expectedUpper"],$sluggerUpper, "Le slug ne correspond pas...");

        }

    }
}
