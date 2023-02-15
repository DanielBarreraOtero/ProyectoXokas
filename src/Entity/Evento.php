<?php

namespace App\Entity;

use App\Repository\EventoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventoRepository::class)]
class Evento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?int $maxAsistentes = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\OneToMany(mappedBy: 'evento', targetEntity: Presentacion::class)]
    private Collection $presentaciones;

    #[ORM\OneToMany(mappedBy: 'evento', targetEntity: Invitacion::class)]
    private Collection $invitaciones;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tramo $tramoInicio = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tramo $tramoFin = null;

    public function __construct()
    {
        $this->presentaciones = new ArrayCollection();
        $this->invitaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMaxAsistentes(): ?int
    {
        return $this->maxAsistentes;
    }

    public function setMaxAsistentes(int $maxAsistentes): self
    {
        $this->maxAsistentes = $maxAsistentes;

        return $this;
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
            $presentacione->setEvento($this);
        }

        return $this;
    }

    public function removePresentacione(Presentacion $presentacione): self
    {
        if ($this->presentaciones->removeElement($presentacione)) {
            // set the owning side to null (unless already changed)
            if ($presentacione->getEvento() === $this) {
                $presentacione->setEvento(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitacion>
     */
    public function getInvitaciones(): Collection
    {
        return $this->invitaciones;
    }

    public function addInvitacione(Invitacion $invitacione): self
    {
        if (!$this->invitaciones->contains($invitacione)) {
            $this->invitaciones->add($invitacione);
            $invitacione->setEvento($this);
        }

        return $this;
    }

    public function removeInvitacione(Invitacion $invitacione): self
    {
        if ($this->invitaciones->removeElement($invitacione)) {
            // set the owning side to null (unless already changed)
            if ($invitacione->getEvento() === $this) {
                $invitacione->setEvento(null);
            }
        }

        return $this;
    }

    public function getTramoInicio(): ?Tramo
    {
        return $this->tramoInicio;
    }

    public function setTramoInicio(?Tramo $tramoInicio): self
    {
        $this->tramoInicio = $tramoInicio;

        return $this;
    }

    public function getTramoFin(): ?Tramo
    {
        return $this->tramoFin;
    }

    public function setTramoFin(?Tramo $tramoFin): self
    {
        $this->tramoFin = $tramoFin;

        return $this;
    }
}
