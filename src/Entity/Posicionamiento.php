<?php

namespace App\Entity;

use App\Repository\PosicionamientoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PosicionamientoRepository::class)]
class Posicionamiento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mesa $mesa = null;

    #[ORM\ManyToOne(inversedBy: 'posicionamientos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Distribucion $distribucion = null;

    #[ORM\Column]
    private ?int $posX = null;

    #[ORM\Column]
    private ?int $posY = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMesa(): ?Mesa
    {
        return $this->mesa;
    }

    public function setMesa(?Mesa $mesa): self
    {
        $this->mesa = $mesa;

        return $this;
    }

    public function getDistribucion(): ?Distribucion
    {
        return $this->distribucion;
    }

    public function setDistribucion(?Distribucion $distribucion): self
    {
        $this->distribucion = $distribucion;

        return $this;
    }

    public function getPosX(): ?int
    {
        return $this->posX;
    }

    public function setPosX(int $posX): self
    {
        $this->posX = $posX;

        return $this;
    }

    public function getPosY(): ?int
    {
        return $this->posY;
    }

    public function setPosY(int $posY): self
    {
        $this->posY = $posY;

        return $this;
    }
}
