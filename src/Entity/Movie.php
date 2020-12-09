<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
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
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Rating;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Watched;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->Rating;
    }

    public function setRating(?int $Rating): self
    {
        $this->Rating = $Rating;

        return $this;
    }

    public function getWatched(): ?bool
    {
        return $this->Watched;
    }

    public function setWatched(bool $Watched): self
    {
        $this->Watched = $Watched;

        return $this;
    }
}
