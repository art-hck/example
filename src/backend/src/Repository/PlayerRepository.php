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

    public function findByCriteria(SeekCriteria $seekCriteria, string $orderBy="id", string $orderDirection="ASC", int $offset = 0, int $limit = 100)
    {
        switch ($orderBy) {
            case "goals": $orderBy="COUNT(goals.id)"; break;
            case "cards": $orderBy="COUNT(cards.id)"; break;
            case "playTime": $orderBy="SUM(s.playTime)"; break;
            default: $orderBy = "p." . $orderBy; break;
        }

        $qb = $this->createQueryBuilder('p')
            ->groupBy('p.id')
            ->orderBy($orderBy, $orderDirection)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
        
        if($seekCriteria->getDatePeriod() || $seekCriteria->getLeagueId()) {
            $qb->join(Game::class, 'g', 'WITH', 'g.homeTeam = p.team OR g.guestTeam = p.team');
            if ($seekCriteria->getDatePeriod()) {
                $qb->andWhere('g.date BETWEEN :from AND :to')
                    ->setParameter('from', $seekCriteria->getDatePeriod()->getStartDate()->format("Y-m-d"))
                    ->setParameter('to', $seekCriteria->getDatePeriod()->getEndDate()->format("Y-m-d"))
                ;
            }

            if ($seekCriteria->getLeagueId()) {
                $qb->andWhere('g.league=:leagueId')
                    ->setParameter('leagueId', $seekCriteria->getLeagueId())
                ;
            }
        }
        
        if($seekCriteria->getTeamId()) {
            $qb->andWhere('p.team=:teamId')
                ->setParameter('teamId', $seekCriteria->getTeamId())
            ;
        }

        if($seekCriteria->getGoalsRange()) {
            $qb
                ->join('p.goals', 'goals')
                ->groupBy('p.id')
                ->having('COUNT(goals.id) BETWEEN :minGoals AND :maxGoals')
                ->setParameter('minGoals', $seekCriteria->getGoalsRange()->min)
                ->setParameter('maxGoals', $seekCriteria->getGoalsRange()->max)
            ;
        }

        if($seekCriteria->getCardsRange()) {
            $qb
                ->join('p.cards', 'cards')
                ->having('COUNT(cards.id) BETWEEN :minCards AND :maxCards')
                ->setParameter('minCards', $seekCriteria->getCardsRange()->min)
                ->setParameter('maxCards', $seekCriteria->getCardsRange()->max)
            ;
            
            if($seekCriteria->getCardsType()) {
                $qb->andWhere('cards.type=:cardsType')
                    ->setParameter('cardsType', $seekCriteria->getCardsType())
                ;
            }
        }
        
        if($seekCriteria->getPlayTimeRange()) {
            $qb
                ->addSelect('SUM(s.playTime)')
                ->join('p.substitutions', 's')
                ->having('SUM(s.playTime) BETWEEN :minPlayTime AND :maxPlayTime')
                ->setParameter('minPlayTime', $seekCriteria->getPlayTimeRange()->min)
                ->setParameter('maxPlayTime', $seekCriteria->getPlayTimeRange()->max)                
            ;
        }


        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}
