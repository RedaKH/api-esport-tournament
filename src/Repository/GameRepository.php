<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

   public function findByTeamAndTournament(Team $team, Tournament $tournament)
   {
     return $this->createQueryBuilder('m')
     ->where('m.teamA = :team OR m.teamB = :team')
     ->andWhere('m.tournament = :tournament')
     ->setParameter('team',$team)
     ->setParameter('tournament',$tournament)
     ->getQuery()
     ->getResult();
   }
   public function findByTournament(Tournament $tournament)
   {
     return $this->createQueryBuilder('m')
     ->Where('m.tournament = :tournament')
     ->setParameter('tournament',$tournament)
     ->getQuery()
     ->getResult();
   }
}
