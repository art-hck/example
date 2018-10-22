<?php

namespace App\Repository;

use App\Entity\Game;
use App\Type\SeekCriteria\SeekCriteria;
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
     * @param array $ids
     * @return Game[]
     */
    public function findByIds(array $ids)
    {
        return $this->createQueryBuilder('game')
            ->andWhere('game.tmId IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByCriteria(SeekCriteria $seekCriteria)
    {
        $qb = $this->createQueryBuilder("g")
            ->where('g.date BETWEEN :from AND :to')
            ->setParameter('from', $seekCriteria->getDatePeriod()->getStartDate()->format('Y-m-d'))
            ->setParameter('to', $seekCriteria->getDatePeriod()->getEndDate()->format('Y-m-d'))
            ->setMaxResults(1)
        ;
        
        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

}
