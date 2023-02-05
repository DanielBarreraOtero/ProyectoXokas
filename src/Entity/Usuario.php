<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $Ap1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ap2 = null;

    #[ORM\Column]
    private ?int $tlf = null;

    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Reserva::class)]
    private Collection $reservas;

    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Invitacion::class)]
    private Collection $invitaciones;

    public function __construct()
    {
        $this->reservas = new ArrayCollection();
        $this->invitaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getAp1(): ?string
    {
        return $this->Ap1;
    }

    public function setAp1(string $Ap1): self
    {
        $this->Ap1 = $Ap1;

        return $this;
    }

    public function getAp2(): ?string
    {
        return $this->Ap2;
    }

    public function setAp2(?string $Ap2): self
    {
        $this->Ap2 = $Ap2;

        return $this;
    }

    public function getTlf(): ?int
    {
        return $this->tlf;
    }

    public function setTlf(int $tlf): self
    {
        $this->tlf = $tlf;

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
            $reserva->setUsuario($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getUsuario() === $this) {
                $reserva->setUsuario(null);
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
            $invitacione->setUsuario($this);
        }

        return $this;
    }

    public function removeInvitacione(Invitacion $invitacione): self
    {
        if ($this->invitaciones->removeElement($invitacione)) {
            // set the owning side to null (unless already changed)
            if ($invitacione->getUsuario() === $this) {
                $invitacione->setUsuario(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id.' | '.$this->email;
    }
}
