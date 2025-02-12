<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\RankingService;

#[Route('/api/rankings')]
class RankingController extends AbstractController
{
    public function __construct(private RankingService $rankingService){

    }
    #[Route('/leaderboard', methods:['GET'])]
    public function getLeaderboard(): JsonResponse{
        return $this->jsonResponse($this->rankingService->getLeaderboard());
    }
    #[Route('/team/{id}/stats', methods:['GET'])]
    public function getTeamStatistics(Team $team): JsonResponse{
        return $this->jsonResponse($this->rankingService->getTeamStatistics($team));

    }


}
