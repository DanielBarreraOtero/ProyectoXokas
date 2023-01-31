<?php

namespace App\Entity;

use App\Repository\JuegoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use stdClass;

#[ORM\Entity(repositoryClass: JuegoRepository::class)]
class Juego implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?float $anchoTablero = null;

    #[ORM\Column]
    private ?float $altoTablero = null;

    #[ORM\Column]
    private ?int $minJugadores = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxJugadores = null;

    #[ORM\OneToMany(mappedBy: 'juego', targetEntity: Presentacion::class)]
    private Collection $presentaciones;

    #[ORM\OneToMany(mappedBy: 'juegos', targetEntity: Reserva::class)]
    private Collection $reservas;

    public function __construct()
    {
        $this->presentaciones = new ArrayCollection();
        $this->reservas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getAnchoTablero(): ?float
    {
        return $this->anchoTablero;
    }

    public function setAnchoTablero(float $anchoTablero): self
    {
        $this->anchoTablero = $anchoTablero;

        return $this;
    }

    public function getAltoTablero(): ?float
    {
        return $this->altoTablero;
    }

    public function setAltoTablero(float $altoTablero): self
    {
        $this->altoTablero = $altoTablero;

        return $this;
    }

    public function getMinJugadores(): ?int
    {
        return $this->minJugadores;
    }

    public function setMinJugadores(int $minJugadores): self
    {
        $this->minJugadores = $minJugadores;

        return $this;
    }

    public function getMaxJugadores(): ?int
    {
        return $this->maxJugadores;
    }

    public function setMaxJugadores(?int $maxJugadores): self
    {
        $this->maxJugadores = $maxJugadores;

        return $this;
    }

    /**
     * @return Collection<int, Presentacion>
     */
    public function getPresentaciones(): Collection
    {
        return $this->presentaciones;
    }

    public function addPresentacione(Presentacion $presentacione): self
    {
        if (!$this->presentaciones->contains($presentacione)) {
            $this->presentaciones->add($presentacione);
            $presentacione->setJuego($this);
        }

        return $this;
    }

    public function removePresentacione(Presentacion $presentacione): self
    {
        if ($this->presentaciones->removeElement($presentacione)) {
            // set the owning side to null (unless already changed)
            if ($presentacione->getJuego() === $this) {
                $presentacione->setJuego(null);
            }
        }

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
            $reserva->setJuegos($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getJuegos() === $this) {
                $reserva->setJuegos(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        $std = new stdClass();

        $std->id = $this->getId();
        $std->nombre = $this->getNombre();
        $std->anchoTablero = $this->getAnchoTablero();
        $std->altoTablero = $this->getAltoTablero();
        $std->minJugadores = $this->getMinJugadores();
        $std->maxJugadores = $this->getMaxJugadores();
        $std->presentaciones = $this->getPresentaciones();
        $std->reservas = $this->getReservas();

        return $std;
    }
}
