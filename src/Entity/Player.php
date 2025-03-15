<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ApiResource(
    normalizationContext:['groups'=>['player:read']],
    denormalizationContext:['groups'=>['player:write']]
)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['player:read','player:write','team:read','team:write'])]
    private ?string $position = null;

    public function __construct()
    {
        // Ne plus appeler parent::__construct()
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): static
    {
        $this->position = $position;

        return $this;
    }

    // Méthodes de délégation pour accéder aux propriétés de User
    public function getEmail(): ?string
    {
        return $this->user ? $this->user->getEmail() : null;
    }

    public function getPseudo(): ?string
    {
        return $this->user ? $this->user->getPseudo() : null;
    }

    public function getRoles(): array
    {
        return $this->user ? $this->user->getRoles() : [];
    }
}
