<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\GameStatus;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Operations;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_ADMIN')"),
        new Post(security: "is_granted('ROLE_ORGANIZER')"),
        new Get(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_ORGANIZER')"),
        new Patch(security: "is_granted('ROLE_ORGANIZER')")
    ],
    normalizationContext: ['groups' => ['game:read']],
    denormalizationContext: ['groups' => ['game:write']]
)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[Groups(['match:read','match:write'])]
    private ?Tournament $tournament = null;


    #[ORM\Column]
    #[Groups(['match:read','match:write'])]
    private array $score = [];

    #[ORM\Column(type: 'string', enumType: GameStatus::class)]
    #[Groups(['match:read','match:write'])]
    private ?GameStatus $status = GameStatus::SCHEDULED;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'gamesAsTeamA')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['match:read','match:write'])]
    private ?Team $teamA = null;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'gamesAsTeamB')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['match:read','match:write'])]
    private ?Team $teamB = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['match:read','match:write'])]
    private ?\DateTimeImmutable $scheduledAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['match:read','match:write'])]
    private ?\DateTimeImmutable $actualstartTime = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['match:read','match:write'])]
    private ?\DateTimeImmutable $actualEndTime = null;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): static
    {
        $this->tournament = $tournament;

        return $this;
    }
    
    public function getScore(): array
    {
        return $this->score;
    }

    public function setScore(array $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getStatus(): GameStatus{
        return $this->status;
    }

    public function setStatus(GameStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTeamA(): ?Team
    {
        return $this->teamA;
    }

    public function setTeamA(?Team $teamA): static
    {
        $this->teamA = $teamA;

        return $this;
    }

    public function getTeamB(): ?Team
    {
        return $this->teamB;
    }

    public function setTeamB(?Team $teamB): static
    {
        $this->teamB = $teamB;

        return $this;
    }

    public function getScheduledAt(): ?\DateTimeImmutable
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(\DateTimeImmutable $scheduledAt): static
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    public function getActualstartTime(): ?\DateTimeImmutable
    {
        return $this->actualstartTime;
    }

    public function setActualstartTime(?\DateTimeImmutable $actualstartTime): static
    {
        $this->actualstartTime = $actualstartTime;

        return $this;
    }

    public function getActualEndTime(): ?\DateTimeImmutable
    {
        return $this->actualEndTime;
    }

    public function setActualEndTime(?\DateTimeImmutable $actualEndTime): static
    {
        $this->actualEndTime = $actualEndTime;

        return $this;
    }

   
}
