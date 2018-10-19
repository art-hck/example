<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Team;
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
     * @param Team $team
     * @param null|string $orderBy
     * @param null|string $orderDirection
     * @return mixed
     */
    public function findByTeam(Team $team, string $orderBy = 'id', string $orderDirection = 'ASC')
    {
        $query = $this->createQueryBuilder('player')
            ->andWhere('player.team = :team')
            ->setParameter('team', $team)
            ->orderBy('player.' . $orderBy, $orderDirection)
            ->getQuery()
        ;

        $players = $query->getResult();

        return $players;
    }

    /*
    public function findOneBySomeField($value): ?Player
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
