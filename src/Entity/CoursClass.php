<?php

namespace App\Entity;

use App\Repository\CoursClassRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursClassRepository::class)]
class CoursClass
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $debutant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $inter = null;

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

    public function getInter(): ?string
    {
        return $this->inter;
    }

    public function setInter(?string $inter): static
    {
        $this->inter = $inter;

        return $this;
    }
}
