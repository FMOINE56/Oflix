<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\OneToMany(targetEntity=Watch::class, mappedBy="user", orphanRemoval=true)
     */
    private $watches;


    public function __construct()
    {
        $this->watches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return Collection<int, Watch>
     */
    public function getWatches(): Collection
    {
        return $this->watches;
    }

    public function addWatch(Watch $watch): self
    {
        if (!$this->watches->contains($watch)) {
            $this->watches[] = $watch;
            $watch->setUser($this);
        }

        return $this;
    }

    public function removeWatch(Watch $watch): self
    {
        if ($this->watches->removeElement($watch)) {
            // set the owning side to null (unless already changed)
            if ($watch->getUser() === $this) {
                $watch->setUser(null);
            }
        }

        return $this;
    }

}
