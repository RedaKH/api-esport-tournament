<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SponsorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Operations;

#[ORM\Entity(repositoryClass: SponsorRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_USER')"),
        new Post(security: "is_granted('ROLE_SPONSOR')"),
        new Get(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_SPONSOR') and object.getManager() == user"),
    ],
    normalizationContext: ['groups' => ['sponsor:read']],
    denormalizationContext: ['groups' => ['sponsor:write']]
)]
class Sponsor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sponsor:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['sponsor:read','sponsor:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['sponsor:read','sponsor:write'])]
    private ?string $logo = null;

    #[ORM\Column(length: 255)]
    #[Groups(['sponsor:read'])]
    private ?string $website = null;

    #[ORM\ManyToOne(inversedBy: 'sponsors')]
    private ?User $user = null;

    /**
     * @var Collection<int, SponsorshipContract>
     */
    #[ORM\OneToMany(targetEntity: SponsorshipContract::class, mappedBy: 'sponsor')]
    private Collection $sponsorshipContracts;

    public function __construct()
    {
        $this->sponsorshipContracts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, SponsorshipContract>
     */
    public function getSponsorshipContracts(): Collection
    {
        return $this->sponsorshipContracts;
    }

    public function addSponsorshipContract(SponsorshipContract $sponsorshipContract): static
    {
        if (!$this->sponsorshipContracts->contains($sponsorshipContract)) {
            $this->sponsorshipContracts->add($sponsorshipContract);
            $sponsorshipContract->setSponsor($this);
        }

        return $this;
    }

    public function removeSponsorshipContract(SponsorshipContract $sponsorshipContract): static
    {
        if ($this->sponsorshipContracts->removeElement($sponsorshipContract)) {
            // set the owning side to null (unless already changed)
            if ($sponsorshipContract->getSponsor() === $this) {
                $sponsorshipContract->setSponsor(null);
            }
        }

        return $this;
    }
}
