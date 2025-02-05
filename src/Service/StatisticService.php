<?php

namespace App\Service;  // Ajout du namespace manquant

use App\Entity\User;
use App\Entity\Tournament;
use App\Entity\Game;
use App\Entity\Team;     // Ajout de l'import manquant
use Doctrine\ORM\EntityManagerInterface;

class StatisticService 
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    

    public function getTournamentPerformance(Team $team): array 
    {
        $tournaments = $this->entityManager->getRepository(Tournament::class)
            ->findByTeam($team);

        $performance = [
            'total_tournaments' => count($tournaments),
            'wins' => 0,
            'losses' => 0,
            'win_rate' => 0,
            'total_prize_money' => 0  // Correction du nom
        ];

        foreach ($tournaments as $tournament) {
            $games = $this->entityManager->getRepository(Game::class)
                ->findByTeamAndTournament($team, $tournament);

            foreach ($games as $game) {
                if ($game->getWinner() === $team) {  // Correction de $match en $game
                    $performance['wins']++;
                } else {
                    $performance['losses']++;
                }
            }

            $performance['total_prize_money'] += $tournament->getPrizePool();
        }

        $performance['win_rate'] = $performance['total_tournaments'] > 0 
            ? round(($performance['wins'] / $performance['total_tournaments']) * 100, 2)
            : 0;

        return $performance;
    }

    public function getTournamentStatistics(Tournament $tournament): array
    {
        $games = $this->entityManager->getRepository(Game::class)
            ->findByTournament($tournament);

        $stats = [
            'total_matches' => count($games),
            'teams_count' => $tournament->getTeams()->count(),
            'total_prize_pool' => $tournament->getPrizePool(),
            'matches_completed' => 0,
            'matches_in_progress' => 0,
            'top_performing_teams' => []
        ];

        foreach ($games as $game) {
            if ($game->getStatus() === 'completed') {
                $stats['matches_completed']++;  // Ajout du point-virgule manquant
            } else {
                $stats['matches_in_progress']++;
            }
        }

        return $stats;
    }

    public function generateAnnualReport(int $year): array 
    {
        $tournaments = $this->entityManager->getRepository(Tournament::class)
            ->findByYear($year);

        $annualReport = [
            'total_tournaments' => count($tournaments),
            'total_prize_money' => 0,
            'total_participants' => 0,
            'most_popular_game' => null,
            'game_distribution' => []
        ];

        foreach ($tournaments as $tournament) {
            $annualReport['total_prize_money'] += $tournament->getPrizePool();
            $annualReport['total_participants'] += $tournament->getTeams()->count();

            $game = $tournament->getGame();
            $annualReport['game_distribution'][$game] = 
                ($annualReport['game_distribution'][$game] ?? 0) + 1;
        }

        $annualReport['most_popular_game'] = array_search(
            max($annualReport['game_distribution']),
            $annualReport['game_distribution']
        );

        return $annualReport;
    }
}