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
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name:'type', type:'string')]
#[ORM\DiscriminatorMap(['user'=>User::class,'player'=>Player::class])]
class Player extends User
{
  

 

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['player:read','player:write','team:read','team:write'])]
    private ?string $position = null;

 

    public function __construct()
    {
        parent::__construct();
        $this->roles = ['ROLE_PLAYER'];

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

   
}
