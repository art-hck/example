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
}
