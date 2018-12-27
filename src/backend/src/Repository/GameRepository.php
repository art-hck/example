<?php

namespace App\Repository;

use App\Entity\Game;
use App\Type\SeekCriteria\Types\SeekCriteriaGameFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @param int $playerId
     * @return Game[]
     */
    public function getByPlayer(int $playerId)
    {
        return $this->createQueryBuilder('g')
            ->join('g.substitutions', 's')
            ->where("s.player = :playerId")
            ->setParameter("playerId", $playerId)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    public function findByCriteria(SeekCriteriaGameFilter $seekCriteria)
    {
        $qb = $this->createQueryBuilder('g')
            ->orderBy("g." . $seekCriteria->getOrderBy(), $seekCriteria->getOrderDirection())
            ->setFirstResult($seekCriteria->getOffset())
            ->setMaxResults($seekCriteria->getLimit())
        ;

        // DATE FILTER
        if ($seekCriteria->getDatePeriod()) {
            /** @var \DateTime $dateFrom */
            $dateFrom = $seekCriteria->getDatePeriod()->min;
            /** @var \DateTime $dateTo */
            $dateTo = $seekCriteria->getDatePeriod()->max;

            $qb
                ->andWhere('g.date >= :dateFrom OR :dateFrom IS NULL')
                ->andWhere('g.date <= :dateTo OR :dateTo IS NULL')
                ->setParameter('dateFrom', $dateFrom ? $dateFrom->format(DATE_ISO8601) : null)
                ->setParameter('dateTo', $dateTo ? $dateTo->format(DATE_ISO8601): null)
            ;
        } // END DATE FILTER

        // TEAM FILTER
        if($seekCriteria->getTeamId()) {
            $qb
                ->join('g.teamGames', 'tg')
                ->andWhere('tg.team = :teamId')
                ->setParameter('teamId', $seekCriteria->getTeamId())
            ;
        } // END TEAM FILTER

        // LEAGUE FILTER
        if($seekCriteria->getLeagueId()) {
            $qb
                ->andWhere('g.league = :leagueId')
                ->setParameter('leagueId', $seekCriteria->getLeagueId())
            ;
        }
        
        if($seekCriteria->getLeagueName()) {
            $qb
                ->join('g.league', 'l')
                ->andWhere('l.name = :leagueName')
                ->setParameter('leagueName', $seekCriteria->getLeagueName())
            ;
        } // END LEAGUE FILTER        
        
        // DURATION FILTER
        if($seekCriteria->getDuration()) {
            $qb
                ->andWhere("g.duration >= :minDuration OR :minDuration IS NULL")
                ->andWhere("g.duration <= :maxDuration OR :maxDuration IS NULL")
                ->setParameter('minDuration', $seekCriteria->getDuration()->min)
                ->setParameter('maxDuration', $seekCriteria->getDuration()->max)
            ;
        } // END TEAM FILTER

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}
