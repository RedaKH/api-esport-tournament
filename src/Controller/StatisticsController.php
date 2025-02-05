<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
Use App\Entity\Team;
Use App\Entity\Tournament;
Use App\Service\StatisticService;
Use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/statistics')]
class StatisticsController extends AbstractController
{
    public function __construct(private StatisticService $statisticService){
    }

    #[Route('/team/{id}/performance', methods: ['GET'])]

    public function getTeamPerformance(Team $team): JsonResponse {

        $performance = $this->StatisticService->getTeamTournamentPerformance($team);
        return $this->json($performance);

    }

    #[Route('/tournament/{id}', methods: ['GET'])]

    public function getTournamentStatistics(Tournament $tournament): JsonResponse {

        $stats = $this->StatisticService->getTournamentStatistics($tournament);
        return $this->json($stats);

    }

    #[Route('/annual-report/{year}', methods: ['GET'])]

    public function getAnnualReport(int $year): JsonResponse {

        $report = $this->StatisticService->generateAnnualReport($year);
        return $this->json($report);

    }
    
}
