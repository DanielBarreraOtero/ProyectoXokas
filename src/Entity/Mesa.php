<?php

namespace App\Entity;

use App\Repository\MesaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use stdClass;

#[ORM\Entity(repositoryClass: MesaRepository::class)]
class Mesa implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $alto = null;

    #[ORM\Column]
    private ?float $ancho = null;

    #[ORM\Column]
    private ?int $posY = null;

    #[ORM\Column]
    private ?int $posX = null;

    #[ORM\Column]
    private ?int $sillas = null;

    #[ORM\OneToMany(mappedBy: 'mesa', targetEntity: Reserva::class)]
    private Collection $reservas;

    public function __construct()
    {
        $this->reservas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlto(): ?float
    {
        return $this->alto;
    }

    public function setAlto(float $alto): self
    {
        $this->alto = $alto;

        return $this;
    }

    public function getAncho(): ?float
    {
        return $this->ancho;
    }

    public function setAncho(float $ancho): self
    {
        $this->ancho = $ancho;

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

    public function getPosX(): ?int
    {
        return $this->posX;
    }

    public function setPosX(int $posX): self
    {
        $this->posX = $posX;

        return $this;
    }

    public function getSillas(): ?int
    {
        return $this->sillas;
    }

    public function setSillas(int $sillas): self
    {
        $this->sillas = $sillas;

        return $this;
    }

    /**
     * @return Collection<int, Reserva>
     */
    public function getReservas(): Collection
    {
        return $this->reservas;
    }

    public function addReserva(Reserva $reserva): self
    {
        if (!$this->reservas->contains($reserva)) {
            $this->reservas->add($reserva);
            $reserva->setMesa($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getMesa() === $this) {
                $reserva->setMesa(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        $std = new stdClass();
        $std->id = $this->getId();
        $std->alto = $this->getAlto();
        $std->ancho = $this->getAncho();
        $std->posX = $this->getPosX();
        $std->posY = $this->getPosY();
        $std->sillas = $this->getSillas();
        $std->reservas = $this->getReservas();

        return $std;
    }
}
