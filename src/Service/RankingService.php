<?php

namespace App\Service;

use App\Entity\Ranking;
use App\Entity\Team;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

class RankingService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateRankingAfterGame(Game $game){
        $teamA = $game->getTeamA();
        $teamB = $game->getTeamB();

        //Points de base pour une victoire/défaite

        $winsPoints =3;
        $losePoints = 0;

        // Calcul des points bonus basés sur les rounds et scores

        $score = $game->getScore();
        $bonusPoints = $this->calculateBonusPoints($score);

        //Mise à jour du classement

        if ($match->getWinner()=== $teamA) {
            $this->updateTeamRanking($teamA,$winsPoints + $bonusPoints, true, $score);
            $this->updateTeamRanking($teamB,$losePoints, false, $score);

            # code...
        } else {
            $this->updateTeamRanking($teamB,$winsPoints + $bonusPoints, true, $score);
            $this->updateTeamRanking($teamA,$losePoints, false, $score);


        }

        //recalcul des rangs

        $this->recalculateAllRanks();
    }

    private function updateTeamRanking(Team $team, int $points, bool $isWinner, array $score){
        $ranking = $this->entityManager->getRepository(Ranking::class)
        ->findOneBy(['team' => $team]) ?? new Ranking();

        if (!$ranking->getId()) {
            $ranking->setTeam($team);

            # code...
        }

        $ranking->setPoints($ranking->getPoints()+ $points);
        $ranking->setMatchesPlayed($ranking->getMatchesPlayed()+1);

        if ($isWinner) {
            $ranking->setMatchesWon($ranking->getMatchesWon()+1);

            # code...
        } else {
            $ranking->setMatchesLost($ranking->getMatchesLost()+1);
        }
        //Mise a jour des rounds

        $roundsWon = $isWinner ? $score['winner'] : $score['loser'];
        $roundsLost = $isWinner ? $score['loser'] : $score['winner'];

        $ranking->setRoundsWon($ranking->getRoundsWon()+ $roundsWon);
        $ranking->setRoundsLost($ranking->getRoundsLost()+ $roundsLost);

        $this->entityManager->persist($ranking);
        $this->entityManager->flush();




    }

    private function calculateBonusPoints(array $score ): int{
        //bonus basé sur l'écart de score
        $scoreDiff = abs($score['winner']- $score['loser']);
        return  match(true){
            $scoreDiff >= 5 => 2, //domination 
            $scoreDiff >= 3 => 1, // victoire confortable
            default => 0 //match serré 
        };
    }

    public function recalculateAllRanks(){
        $rankings = $this->entityManager->getRepository(Ranking::class)
        ->findBy([],['points'=>'DESC','roundsWon'=>'DESC']);

        $rank = 1;

        foreach($rankings as $ranking){
            $ranking->setRank($rank++);

        }
        $this->entityManager->flush();

    }

    public function getTeamStatistics(Team $team): array{
        $ranking = $this->entityManager->getRepository(Ranking::class)
        ->findOneBy(['team' => $team]);

        if(!$ranking){
            return
                [
                    'rank'=>0,
                'points'=>0,
                'matches_played'=>0,
                'win_rate'=>0,
                'rounds_ratio'=>0

                ];
                

            
        }
        $winsRate = $ranking->getMatchesPlayed()> 0 
        ? ($ranking->getMatchesWon()/$ranking->getMatchesPlayed())*100:0;

        $roundsRatio = $ranking->getRoundsLost()> 0 
        ? $ranking->getRoundsWon()/$ranking->getRoundsLost()
        :$ranking->getRoundsWon();

        return [
            'rank'=>$ranking->getRank(),
            'points'=>$ranking->getPoints(),
            'matches_played'=>$ranking->getMatchesPlayed(),
            'win_rate'=>round($winsRate,2),
            'rounds_ratio'=>round($roundsRatio,2)
        ];

    }

    public function getLeaderboard(int $limit = 10): array{
        return $this->entityManager->getRepository(Ranking::class)
        ->findBy(
            [],
            ['points'=>'DESC','roundsWon'=> 'DESC'],
            $limit
        );
    }



  
}

?>