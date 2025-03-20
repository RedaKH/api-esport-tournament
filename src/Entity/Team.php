<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Operations;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\State\TeamProcessor;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('PUBLIC_ACCESS')"),
        new Post(security: "is_granted('ROLE_MANAGER')",
                 processor:TeamProcessor::class),
        new Get(security: "is_granted('IS_AUTHENTICATED_FULLY')"),
        new Put(security: "is_granted('ROLE_MANAGER') and object.getManager() == user"),
        new Patch(security: "is_granted('ROLE_MANAGER') and object.getManager() == user")
    ],
    normalizationContext: ['groups' => ['team:read']],
    denormalizationContext: ['groups' => ['team:write']]
)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['team:read','team:write'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['team:read','team:write'])]
    private ?string $logo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['team:read','team:write'])]
    private ?string $description = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'teams')]
    #[Groups(['team:read', 'team:write'])]
    private Collection $manager;

    /**
     * @var Collection<int, Tournament>
     */
    #[ORM\ManyToMany(targetEntity: Tournament::class, mappedBy: 'teams')]

    private Collection $tournaments;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'teamA')]
    private Collection $gamesAsTeamA;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'teamB')]
    private Collection $gamesAsTeamB;

    /**
     * @var Collection<int, SponsorshipContract>
     */
    #[ORM\OneToMany(targetEntity: SponsorshipContract::class, mappedBy: 'Team')]
    private Collection $sponsorshipContracts;

    /**
     * @var Collection<int, Ranking>
     */
    #[ORM\OneToMany(targetEntity: Ranking::class, mappedBy: 'Team')]
    private Collection $rankings;

    /**
     * @var Collection<int, Player>
     */
    #[ORM\ManyToMany(targetEntity: Player::class)]
    private Collection $players;

    public function __construct()
    {
        $this->manager = new ArrayCollection();
        $this->tournaments = new ArrayCollection();
        $this->gamesAsTeamA = new ArrayCollection();
        $this->gamesAsTeamB = new ArrayCollection();
        $this->sponsorshipContracts = new ArrayCollection();
        $this->rankings = new ArrayCollection();
        $this->players = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getManager(): Collection
    {
        return $this->manager;
    }

    public function addManager(User $manager): static
    {
        if (!$this->manager->contains($manager)) {
            $this->manager->add($manager);
        }

        return $this;
    }

    public function removeManager(User $manager): static
    {
        $this->manager->removeElement($manager);

        return $this;
    }

    /**
     * @return Collection<int, Tournament>
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    public function addTournament(Tournament $tournament): static
    {
        if (!$this->tournaments->contains($tournament)) {
            $this->tournaments->add($tournament);
            $tournament->addTeam($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): static
    {
        if ($this->tournaments->removeElement($tournament)) {
            $tournament->removeTeam($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGamesAsTeamA(): Collection
    {
        return $this->gamesAsTeamA;
    }

    public function addGameAsTeamA(Game $game): static
    {
        if (!$this->gamesAsTeamA->contains($game)) {
            $this->gamesAsTeamA->add($game);
            $game->setTeamA($this);
        }

        return $this;
    }

    public function removeGameAsTeamA(Game $game): static
    {
        if ($this->gamesAsTeamA->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getTeamA() === $this) {
                $game->setTeamA(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGamesAsTeamB(): Collection
    {
        return $this->gamesAsTeamB;
    }

    public function addGameAsTeamB(Game $game): static
    {
        if (!$this->gamesAsTeamB->contains($game)) {
            $this->gamesAsTeamB->add($game);
            $game->setTeamB($this);
        }

        return $this;
    }

    public function removeGameAsTeamB(Game $game): static
    {
        if ($this->gamesAsTeamB->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getTeamB() === $this) {
                $game->setTeamB(null);
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
            $sponsorshipContract->setTeam($this);
        }

        return $this;
    }

    public function removeSponsorshipContract(SponsorshipContract $sponsorshipContract): static
    {
        if ($this->sponsorshipContracts->removeElement($sponsorshipContract)) {
            // set the owning side to null (unless already changed)
            if ($sponsorshipContract->getTeam() === $this) {
                $sponsorshipContract->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ranking>
     */
    public function getRankings(): Collection
    {
        return $this->rankings;
    }

    public function addRanking(Ranking $ranking): static
    {
        if (!$this->rankings->contains($ranking)) {
            $this->rankings->add($ranking);
            $ranking->setTeam($this);
        }

        return $this;
    }

    public function removeRanking(Ranking $ranking): static
    {
        if ($this->rankings->removeElement($ranking)) {
            // set the owning side to null (unless already changed)
            if ($ranking->getTeam() === $this) {
                $ranking->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        if ($this->players->removeElement($player)) {
        }

        return $this;
    }
}
