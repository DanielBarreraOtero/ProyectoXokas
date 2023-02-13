<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use stdClass;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
class Reserva implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $asiste = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tramo $tramo = null;


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

    public function jsonSerialize()
    {
        $std = new stdClass();

        $std->id = $this->getId();
        $std->mesa_id = $this->getMesa()->getId();
        $std->juego_id = $this->getJuegos()->getId();
        $std->usuario_id = $this->getUsuario()->getId();
        $std->asiste = $this->isAsiste();
        $std->fecha = $this->getFecha();
        $std->fecha_cancelacion = $this->getFechaCancelacion();

        return $std;
    }

    public function getTramo(): ?Tramo
    {
        return $this->tramo;
    }

    public function setTramo(?Tramo $tramo): self
    {
        $this->tramo = $tramo;

        return $this;
    }
}
