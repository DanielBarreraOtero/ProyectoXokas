<?php

namespace App\Entity;

use App\Repository\PosicionamientoRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use stdClass;

#[ORM\Entity(repositoryClass: PosicionamientoRepository::class)]
class Posicionamiento implements JsonSerializable
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

    public function jsonSerialize(): mixed
    {
        $std = new stdClass();

        $std->id = $this->getId();
        $std->mesa_id = $this->getMesa()->getId();
        $std->distribucion_id = $this->getDistribucion()->getId();
        $std->posX = $this->getPosX();
        $std->posY = $this->getPosY();

        return $std;
    }

    public function __toString()
    {
        return $this->id . ' | ' . $this->distribucion->getId() . ' - ' . $this->mesa->getId();
    }
}
