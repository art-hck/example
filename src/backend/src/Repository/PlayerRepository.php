<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\TeamGame;
use App\Type\SeekCriteria\Types\SeekCriteriaPlayerFilter;
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

    public function findByCriteria(SeekCriteriaPlayerFilter $seekCriteria)
    {
        switch ($seekCriteria->getOrderBy()) {
            case "goals": $orderBy="COUNT(goals.id)"; break;
            case "cards": $orderBy="COUNT(cards.id)"; break;
            case "playTime": $orderBy="SUM(s.playTime)"; break;
            default: $orderBy = "p." . $seekCriteria->getOrderBy(); break;
        }

        $qb = $this->createQueryBuilder('p')
            ->join(TeamGame::class, 'tg', 'WITH', 'tg.team = p.team')
            ->join(Game::class, 'g', 'WITH', 'tg.game = g.id')
            ->groupBy('p.id')
            ->orderBy($orderBy, $seekCriteria->getOrderDirection())
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
        

        // LEAGUE FILTER
        if ($seekCriteria->getLeagueId()) {
            $qb->andWhere('p.league=:leagueId')
                ->setParameter('leagueId', $seekCriteria->getLeagueId())
            ;
        }

//        @TODO: implement!
//        $qb->join('g.league', 'l')
//            ->andWhere('l.name LIKE :leagueName')
//            ->setParameter("leagueName", "1. Liga group 1%")
//        ;
        // END LEAGUE FILTER
        

        // TEAM FILTER
        if($seekCriteria->getTeamId()) {
            $qb->andWhere('tg.team=:teamId')
                ->setParameter('teamId', $seekCriteria->getTeamId())
            ;
        } // END TEAM FILTER
        
        
        // GOALS FILTER
        if($seekCriteria->getGoalsRange()) {
            $qb
                ->join('p.goals', 'goals')
                ->andHaving('COUNT(goals.id) >= :minGoals OR :minGoals IS NULL')
                ->andHaving('COUNT(goals.id) <= :maxGoals OR :maxGoals IS NULL')
                ->setParameter('minGoals', $seekCriteria->getGoalsRange()->min)
                ->setParameter('maxGoals', $seekCriteria->getGoalsRange()->max)
            ;
        } // END GOALS FILTER
        

        // CARDS FILTER
        if($seekCriteria->getCardsRange() || $seekCriteria->getCardsType()) {
            $qb->join('p.cards', 'cards');
        }

        if($seekCriteria->getCardsRange()) {
            $qb
                ->andHaving('COUNT(cards.id) >= :minCards OR :minCards IS NULL')
                ->andHaving('COUNT(cards.id) <= :maxCards OR :maxCards IS NULL')
                ->setParameter('minCards', $seekCriteria->getCardsRange()->min)
                ->setParameter('maxCards', $seekCriteria->getCardsRange()->max)
            ;
        }
        
        if($seekCriteria->getCardsType()) {
            $qb->andWhere('cards.type=:cardsType')
                ->setParameter('cardsType', $seekCriteria->getCardsType())
            ;
        } // END CARDS FILTER
        
        // PLAY TIME FILTER
        if($seekCriteria->getPlayTimeRange()) { 
            $qb
                ->join('p.substitutions', 's')
                ->andHaving('SUM(s.playTime) >= :minPlayTime OR :minPlayTime IS NULL')
                ->andHaving('SUM(s.playTime) <= :maxPlayTime OR :maxPlayTime IS NULL')
                ->setParameter('minPlayTime', $seekCriteria->getPlayTimeRange()->min)
                ->setParameter('maxPlayTime', $seekCriteria->getPlayTimeRange()->max)
            ;
        } // END PLAY TIME FILTER
        
        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}