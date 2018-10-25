<?php

namespace App\Repository;

use App\Entity\TeamGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TeamGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamGame[]    findAll()
 * @method TeamGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamGameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TeamGame::class);
    }

//    /**
//     * @return TeamGame[] Returns an array of TeamGame objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeamGame
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
