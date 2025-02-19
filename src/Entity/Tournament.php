<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\TournamentStatus;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Operations;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\State\TournamentProcessor;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_ADMIN')"),
        new Post(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_ORGANIZER')",
         processor: TournamentProcessor::class),
        new Get(security: "is_granted('ROLE_USER') and object == user"),
        new Put(security: "is_granted('ROLE_ORGANIZER') and object.getOrganizer() == user"),
        new Patch(security: "is_granted('ROLE_ORGANIZER') and object.getOrganizer() == user")
    ],
    normalizationContext: ['groups' => ['tournament:read']],
    denormalizationContext: ['groups' => ['tournament:write']]
)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tournament:read','tournament:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tournament:read','tournament:write'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]

    #[Groups(['tournament:read','tournament:write'])]

    private ?string $game = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['tournament:read','tournament:write'])]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['tournament:read','tournament:write'])]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    #[Groups(['tournament:read','tournament:write'])]
    private ?float $prizePool = 0;

    #[ORM\Column]
    #[Groups(['tournament:read','tournament:write'])]
    private ?float $registrationFee = 0;



    #[ORM\ManyToOne(inversedBy: 'tournaments')]
    #[Groups(['tournament:read'])]
    private ?User $organizer = null;

    /**
     * @var Collection<int, Team>
     */
    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: 'tournaments')]
    #[Groups(['tournament:read'])]

    private Collection $teams;

    #[ORM\Column(enumType: TournamentStatus::class)]
    private ?TournamentStatus $status = TournamentStatus::REGISTRATION;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'tournament')]
    private Collection $games;

    /**
     * @var Collection<int, SponsorshipContract>
     */
    #[ORM\OneToMany(targetEntity: SponsorshipContract::class, mappedBy: 'Tournament')]
    private Collection $sponsorshipContracts;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'Tournament')]
    private Collection $payments;


  

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->sponsorshipContracts = new ArrayCollection();
        $this->payments = new ArrayCollection();
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

    public function getGame(): ?string
    {
        return $this->game;
    }

    public function setGame(string $game): static
    {
        $this->game = $game;

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

    public function getPrizePool(): ?float
    {
        return $this->prizePool;
    }

    public function setPrizePool(float $prizePool): static
    {
        $this->prizePool = $prizePool;

        return $this;
    }

    public function getRegistrationFee(): ?float
    {
        return $this->registrationFee;
    }

    public function setRegistrationFee(float $registrationFee): static
    {
        $this->registrationFee = $registrationFee;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): static
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        $this->teams->removeElement($team);

        return $this;
    }

    public function getStatus(): ?TournamentStatus
    {
        return $this->status;
    }

    public function setStatus(TournamentStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setTournament($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getTournament() === $this) {
                $game->setTournament(null);
            }
        }

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
            $sponsorshipContract->setTournament($this);
        }

        return $this;
    }

    public function removeSponsorshipContract(SponsorshipContract $sponsorshipContract): static
    {
        if ($this->sponsorshipContracts->removeElement($sponsorshipContract)) {
            // set the owning side to null (unless already changed)
            if ($sponsorshipContract->getTournament() === $this) {
                $sponsorshipContract->setTournament(null);
            }
        }

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
            $payment->setTournament($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getTournament() === $this) {
                $payment->setTournament(null);
            }
        }

        return $this;
    }

    




}
