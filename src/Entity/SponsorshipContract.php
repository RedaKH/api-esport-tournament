<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\SponsorshipLevel;
use App\Repository\SponsorshipContractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operations;

#[ORM\Entity(repositoryClass: SponsorshipContractRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_USER')"),
        new Post(security: "is_granted('ROLE_SPONSOR')"),
        new Get(security: "is_granted('ROLE_USER')"),

       
    ],
    normalizationContext: ['groups' => ['contract:read']],
    denormalizationContext: ['groups' => ['contract:write']]
)]
class SponsorshipContract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contract:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sponsorshipContracts')]
    #[Groups(['contract:read','contract:write'])]
    private ?Sponsor $sponsor = null;

    #[ORM\ManyToOne(inversedBy: 'sponsorshipContracts')]
    #[Groups(['contract:read','contract:write'])]
    private ?Team $Team = null;

    #[ORM\ManyToOne(inversedBy: 'sponsorshipContracts')]
    #[Groups(['contract:read','contract:write'])]

    private ?Tournament $Tournament = null;

    #[ORM\Column(enumType: SponsorshipLevel::class)]
    #[Groups(['contract:read','contract:write'])]

    private ?SponsorshipLevel $level = null;

    #[ORM\Column]
    #[Groups(['contract:read','contract:write'])]

    private ?float $amount = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['contract:read','contract:write'])]

    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['contract:read','contract:write'])]

    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['contract:read','contract:write'])]

    private ?string $terms = null;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'sponsorshipContract')]
    private Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSponsor(): ?Sponsor
    {
        return $this->sponsor;
    }

    public function setSponsor(?Sponsor $sponsor): static
    {
        $this->sponsor = $sponsor;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->Team;
    }

    public function setTeam(?Team $Team): static
    {
        $this->Team = $Team;

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->Tournament;
    }

    public function setTournament(?Tournament $Tournament): static
    {
        $this->Tournament = $Tournament;

        return $this;
    }

    public function getLevel(): ?SponsorshipLevel
    {
        return $this->level;
    }

    public function setLevel(SponsorshipLevel $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getTerms(): ?string
    {
        return $this->terms;
    }

    public function setTerms(string $terms): static
    {
        $this->terms = $terms;

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setSponsorshipContract($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getSponsorshipContract() === $this) {
                $payment->setSponsorshipContract(null);
            }
        }

        return $this;
    }
}
