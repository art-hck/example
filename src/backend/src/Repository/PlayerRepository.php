<?php

namespace App\Repository;

use App\Entity\Player;
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
            case "name": $orderBy="p.firstName, p.lastName"; break;
            case "team": $orderBy="t.name"; break;
            default: $orderBy = "p." . $seekCriteria->getOrderBy(); break;
        }

        $qb = $this->createQueryBuilder('p')
            ->join('p.team', 't')
            ->groupBy('p.id')
            ->orderBy($orderBy, $seekCriteria->getOrderDirection())
            ->setFirstResult($seekCriteria->getOffset())
            ->setMaxResults($seekCriteria->getLimit())
        ;
        
        if($seekCriteria->getOrderBy() == "name") {
            $qb
                ->andWhere("p.firstName IS NOT NULL")
                ->andWhere("p.lastName IS NOT NULL");
        } else {
            $qb->andWhere($orderBy . " IS NOT NULL");
        }

        if (
            $seekCriteria->getDatePeriod() || 
            $seekCriteria->getLeagueId() || 
            $seekCriteria->getGoalsRange() || 
            $seekCriteria->getAssistsRange() || 
            $seekCriteria->getCardsRange() || 
            $seekCriteria->getIsInternational() || 
            $seekCriteria->getCardsType() || 
            $seekCriteria->getPlayTimeRange()) 
        {
            $qb
                ->join('t.teamGames', 'tg')
                ->join('tg.game', 'g')
            ;
        }

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
        if ($seekCriteria->getLeagueName() || $seekCriteria->getIsInternational()) {
            $qb->join('g.league', 'l');
        }

        if ($seekCriteria->getLeagueId()) {
            $qb->andWhere('g.league=:leagueId')
                ->setParameter('leagueId', $seekCriteria->getLeagueId())
            ;
        }

        if ($seekCriteria->getLeagueName()) {
            $qb
                ->andWhere('l.name LIKE :leagueName')
                ->setParameter('leagueName', $seekCriteria->getLeagueName() . "%")
            ;
        } // END LEAGUE FILTER
        
        // INTERNATIONAL FILTER
        if($seekCriteria->getIsInternational()) {
            $qb
                ->andWhere('l.isInternational = :isInternational')
                ->setParameter('isInternational', $seekCriteria->getIsInternational())
            ;
        } // END INTERNATIONAL FILTER

        // TEAM FILTER
        if($seekCriteria->getTeamId()) {
            $qb->andWhere('p.team=:teamId')
                ->setParameter('teamId', $seekCriteria->getTeamId())
            ;
        }
        
        if ($seekCriteria->getTeamName()) {
            $qb->andWhere('t.name LIKE :teamName')
                ->setParameter('teamName', $seekCriteria->getTeamName() . "%")
            ;
        } // END TEAM FILTER

        // AGE FILTER
        if($seekCriteria->getAgeRange()) {
            if($seekCriteria->getAgeRange()->max) {
                $minBirthday = (new \DateTime())
                    ->sub(new \DateInterval('P' . ($seekCriteria->getAgeRange()->max) . 'Y'))
                    ->format("Y-m-d")
                ;
            }

            if($seekCriteria->getAgeRange()->min) {
                $maxBirthday = (new \DateTime())
                    ->sub(new \DateInterval('P' . ($seekCriteria->getAgeRange()->min + 1) . 'Y'))
                    ->add(new \DateInterval('P1D'))
                    ->format("Y-m-d")
                ;
            }
            
            $qb
                ->andWhere('p.birthday >= :minBirthday OR :minBirthday IS NULL')
                ->andWhere('p.birthday <= :maxBirthday OR :maxBirthday IS NULL')
                ->setParameter('minBirthday', $minBirthday ?? null)
                ->setParameter('maxBirthday', $maxBirthday ?? null)
            ;
        } // END AGE FILTER
        
        //ROLE FILTER
        if($seekCriteria->getRole()) {
            $qb
                ->andWhere("p.role = :role")
                ->setParameter("role", $seekCriteria->getRole()->getId())
            ;
        } // END ROLE FILTER

        //HEIGHT FILTER
        if($seekCriteria->getHeightRange()) {
            $qb
                ->andWhere("p.height >= :minHeight OR :minHeight IS NULL")
                ->andWhere("p.height <= :maxHeight OR :maxHeight IS NULL")
                ->setParameter('minHeight', $seekCriteria->getHeightRange()->min)
                ->setParameter('maxHeight', $seekCriteria->getHeightRange()->max)
            ;
        } // END ROLE FILTER
        
        // GOALS FILTER
        if($seekCriteria->getGoalsRange() || $seekCriteria->getAssistsRange()) {
            $qb->join('p.goals', 'goals', 'WITH', 'goals.game = g.id');
        }

        if($seekCriteria->getGoalsRange()) {
            $qb
                ->andHaving('COUNT(goals.id) >= :minGoals OR :minGoals IS NULL')
                ->andHaving('COUNT(goals.id) <= :maxGoals OR :maxGoals IS NULL')
                ->setParameter('minGoals', $seekCriteria->getGoalsRange()->min)
                ->setParameter('maxGoals', $seekCriteria->getGoalsRange()->max)
            ;
        } // END GOALS FILTER

        // ASSISTS FILTER
        if($seekCriteria->getAssistsRange()) {
            $qb
                ->join('goals.assist', 'assist')
                ->andHaving('COUNT(assist.id) >= :minAssist OR :minAssist IS NULL')
                ->andHaving('COUNT(goals.id) <= :maxAssist OR :maxAssist IS NULL')
                ->setParameter('minAssist', $seekCriteria->getAssistsRange()->min)
                ->setParameter('maxAssist', $seekCriteria->getAssistsRange()->max)
            ;
        } // END ASSISTS FILTER

        // CARDS FILTER
        if($seekCriteria->getCardsRange() || $seekCriteria->getCardsType()) {
            $qb->join('p.cards', 'cards', 'WITH', 'cards.game = g.id');
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
                ->join('p.substitutions', 's', 'WITH', 's.game = g.id')
                ->andHaving('SUM(s.playTime) >= :minPlayTime OR :minPlayTime IS NULL')
                ->andHaving('SUM(s.playTime) <= :maxPlayTime OR :maxPlayTime IS NULL')
                ->setParameter('minPlayTime', $seekCriteria->getPlayTimeRange()->min)
                ->setParameter('maxPlayTime', $seekCriteria->getPlayTimeRange()->max)
            ;
        } // END PLAY TIME FILTER

//        echo $qb->getQuery()->getSQL();die;
        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}