<?php

namespace App\Entity;

use App\Enum\PaymentStatus;
use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operations;
use ApiPlatform\Metadata\ApiResource;


#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_USER')", normalizationContext: ['groups'=>['payment:read']]),
        new Post(security: "is_granted('ROLE_SPONSOR') and object.getUser() == user" , normalizationContext: ['groups'=>['payment:read']]),
        new Get(security: "is_granted('ROLE_USER')", denormalizationContext: ['groups'=>['payment:write']]),
    ]
    
)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['payment:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[Groups(['payment:read'])]
    private ?User $User = null;

    #[ORM\Column]
    #[Groups(['payment:read','payment:write'])]
    private ?float $amount = null;

    #[ORM\Column(length: 255)]
    #[Groups(['payment:read'])]
    private ?string $stripePaymentIntentId = null;

    #[ORM\Column(enumType: PaymentStatus::class)]
    #[Groups(['payment:read'])]
    private ?PaymentStatus $status = PaymentStatus::PENDING;

    #[ORM\ManyToOne(inversedBy: 'payments' )]
    #[Groups(['payment:read','payment:write'])]
    private ?SponsorshipContract $sponsorshipContract = null;

    #[ORM\Column]
    #[Groups(['payment:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    private ?Tournament $Tournament = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

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

    public function getStripePaymentIntentId(): ?string
    {
        return $this->stripePaymentIntentId;
    }

    public function setStripePaymentIntentId(string $stripePaymentIntentId): static
    {
        $this->stripePaymentIntentId = $stripePaymentIntentId;

        return $this;
    }

    public function getStatus(): ?PaymentStatus
    {
        return $this->status;
    }

    public function setStatus(PaymentStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSponsorshipContract(): ?SponsorshipContract
    {
        return $this->sponsorshipContract;
    }

    public function setSponsorshipContract(?SponsorshipContract $sponsorshipContract): static
    {
        $this->sponsorshipContract = $sponsorshipContract;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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
}
