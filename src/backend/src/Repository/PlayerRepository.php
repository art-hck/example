<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Player;
use App\Type\SeekCriteria\SeekCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Player::class);
    }


    /**
     * @param array $ids
     * @return Player[]
     */
    public function findByIds(array $ids)
    {
        return $this->createQueryBuilder('player')
            ->andWhere('player.tmId IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCriteria(SeekCriteria $seekCriteria)
    {
        $qb = $this->createQueryBuilder('p')
            ->join(Game::class, 'g', 'WITH', 'g.homeTeam = p.team OR g.guestTeam = p.team')
        ;
        
        if($seekCriteria->getDatePeriod()) {
            $qb->where('g.date BETWEEN :from AND :to')
                ->setParameter('from', $seekCriteria->getDatePeriod()->getStartDate()->format("Y-m-d"))
                ->setParameter('to', $seekCriteria->getDatePeriod()->getEndDate()->format("Y-m-d"))
            ;
        }

        if($seekCriteria->getLeagueId()) {
            $qb->andWhere('g.league=:leagueId')
                ->setParameter('leagueId', $seekCriteria->getLeagueId())
            ;
        }
        
        if($seekCriteria->getTeamId()) {
            $qb->andWhere('p.team=:teamId')
                ->setParameter('teamId', $seekCriteria->getTeamId())
            ;
        }
        
        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}
