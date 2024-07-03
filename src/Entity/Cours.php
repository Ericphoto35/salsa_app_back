<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $debutant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebutant(): ?string
    {
        return $this->debutant;
    }

    public function setDebutant(?string $debutant): static
    {
        $this->debutant = $debutant;

        return $this;
    }
}
