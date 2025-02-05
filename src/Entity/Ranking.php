<?php

namespace App\Entity;

use App\Repository\RankingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RankingRepository::class)]
class Ranking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rankings')]
    private ?Team $Team = null;

    #[ORM\Column]
    private ?int $points = null;

    #[ORM\Column]
    private ?int $ranked = null;

    #[ORM\Column]
    private ?int $matchesWon = null;

    #[ORM\Column]
    private ?int $matchesLost = null;

    #[ORM\Column]
    private ?int $roundsWon = null;

    #[ORM\Column]
    private ?int $roundsLost = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getRanked(): ?int
    {
        return $this->ranked;
    }

    public function setRanked(int $ranked): static
    {
        $this->ranked = $ranked;

        return $this;
    }

    public function getMatchesWon(): ?int
    {
        return $this->matchesWon;
    }

    public function setMatchesWon(int $matchesWon): static
    {
        $this->matchesWon = $matchesWon;

        return $this;
    }

    public function getMatchesLost(): ?int
    {
        return $this->matchesLost;
    }

    public function setMatchesLost(int $matchesLost): static
    {
        $this->matchesLost = $matchesLost;

        return $this;
    }

    public function getRoundsWon(): ?int
    {
        return $this->roundsWon;
    }

    public function setRoundsWon(int $roundsWon): static
    {
        $this->roundsWon = $roundsWon;

        return $this;
    }

    public function getRoundsLost(): ?int
    {
        return $this->roundsLost;
    }

    public function setRoundsLost(int $roundsLost): static
    {
        $this->roundsLost = $roundsLost;

        return $this;
    }
}
