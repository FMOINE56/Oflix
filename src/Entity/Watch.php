<?php

namespace App\Entity;

use App\Repository\WatchRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WatchRepository::class)
 */
class Watch
{
 

    /**
     * @ORM\Column(type="integer")
     */
    private $timeSecond;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="watches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Movie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;


    public function getTimeSecond(): ?int
    {
        return $this->timeSecond;
    }

    public function setTimeSecond(int $timeSecond): self
    {
        $this->timeSecond = $timeSecond;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
