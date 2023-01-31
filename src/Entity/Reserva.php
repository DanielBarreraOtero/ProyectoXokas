<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $asiste = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\ManyToMany(targetEntity: Tramo::class)]
    private Collection $tramos;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mesa $mesa = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Juego $juegos = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaCancelacion = null;

    public function __construct()
    {
        $this->tramos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isAsiste(): ?bool
    {
        return $this->asiste;
    }

    public function setAsiste(bool $asiste): self
    {
        $this->asiste = $asiste;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return Collection<int, Tramo>
     */
    public function getTramos(): Collection
    {
        return $this->tramos;
    }

    public function addTramo(Tramo $tramo): self
    {
        if (!$this->tramos->contains($tramo)) {
            $this->tramos->add($tramo);
        }

        return $this;
    }

    public function removeTramo(Tramo $tramo): self
    {
        $this->tramos->removeElement($tramo);

        return $this;
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

    public function getJuegos(): ?Juego
    {
        return $this->juegos;
    }

    public function setJuegos(?Juego $juegos): self
    {
        $this->juegos = $juegos;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getFechaCancelacion(): ?\DateTimeInterface
    {
        return $this->fechaCancelacion;
    }

    public function setFechaCancelacion(?\DateTimeInterface $fechaCancelacion): self
    {
        $this->fechaCancelacion = $fechaCancelacion;

        return $this;
    }
}
