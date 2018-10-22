<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Team;
use App\Type\SeekCriteria\SeekCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $qb = $this->createQueryBuilder("p")
            ->select('p')
            ->leftJoin(Team::class, 't')
            ->where('p.alias = 1')
                //            ->where('g.date BETWEEN :from AND :to')
//            ->setParameter('from', $seekCriteria->getDatePeriod()->getStartDate()->format('Y-m-d'))
//            ->setParameter('to', $seekCriteria->getDatePeriod()->getEndDate()->format('Y-m-d'))
            ->setMaxResults(10)
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
    
    
}
