<?php

namespace App\Entity;

use App\Repository\DistribucionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DistribucionRepository::class)]
class Distribucion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\OneToMany(mappedBy: 'distribucion', targetEntity: Posicionamiento::class)]
    private Collection $posicionamientos;

    public function __construct()
    {
        $this->posicionamientos = new ArrayCollection();
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

    /**
     * @return Collection<int, Posicionamiento>
     */
    public function getPosicionamientos(): Collection
    {
        return $this->posicionamientos;
    }

    public function addPosicionamiento(Posicionamiento $posicionamiento): self
    {
        if (!$this->posicionamientos->contains($posicionamiento)) {
            $this->posicionamientos->add($posicionamiento);
            $posicionamiento->setDistribucion($this);
        }

        return $this;
    }

    public function removePosicionamiento(Posicionamiento $posicionamiento): self
    {
        if ($this->posicionamientos->removeElement($posicionamiento)) {
            // set the owning side to null (unless already changed)
            if ($posicionamiento->getDistribucion() === $this) {
                $posicionamiento->setDistribucion(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id.' | '.$this->fecha->format('d/m/Y');
    }
}
