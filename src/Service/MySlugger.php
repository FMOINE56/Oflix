<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class MySlugger{

    private $slugger;
    private $toLower;

    public function __construct(SluggerInterface $slugger, bool $toLower)
    {
        $this->slugger = $slugger;
        $this->toLower = $toLower;
    }

    /**
     * @param string $string the string to slugify
     * @return string the string slugified
     */
    public function slugify(string $string) :string{

        return $this->toLower ? $this->slugger->slug($string)->lower() : $this->slugger->slug($string);
        
    }
}