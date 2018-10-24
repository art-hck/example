<?php

namespace App\Repository;

use App\Entity\Substitution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Substitution|null find($id, $lockMode = null, $lockVersion = null)
 * @method Substitution|null findOneBy(array $criteria, array $orderBy = null)
 * @method Substitution[]    findAll()
 * @method Substitution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubstitutionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Substitution::class);
    }
}
