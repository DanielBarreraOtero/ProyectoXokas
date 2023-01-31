<?php

namespace App\Entity;

use App\Repository\DiaFestivoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiaFestivoRepository::class)]
class DiaFestivo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dia = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDia(): ?\DateTimeInterface
    {
        return $this->dia;
    }

    public function setDia(\DateTimeInterface $dia): self
    {
        $this->dia = $dia;

        return $this;
    }
}
