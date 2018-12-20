<?php

namespace App\Repository;

use App\Entity\Transfer;
use App\Type\SeekCriteria\Types\SeekCriteriaTransferFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Transfer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transfer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transfer[]    findAll()
 * @method Transfer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transfer::class);
    }
    
    public function findByCriteria(SeekCriteriaTransferFilter $seekCriteria) 
    {
        $qb = $this->createQueryBuilder('t')
            ->orderBy("t." . $seekCriteria->getOrderBy(), $seekCriteria->getOrderDirection())
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
                ->andWhere('t.date >= :dateFrom OR :dateFrom IS NULL')
                ->andWhere('t.date <= :dateTo OR :dateTo IS NULL')
                ->setParameter('dateFrom', $dateFrom ? $dateFrom->format(DATE_ISO8601) : null)
                ->setParameter('dateTo', $dateTo ? $dateTo->format(DATE_ISO8601): null)
            ;
        } // END DATE FILTER
        
        // FEE FILTER
        if ($seekCriteria->getFeeRange()) {
            $qb
                ->andWhere("t.fee >= :minFee OR :minFee IS NULL")
                ->andWhere("t.fee <= :maxFee OR :maxFee IS NULL")
                ->setParameter('minFee', $seekCriteria->getFeeRange()->min)
                ->setParameter('maxFee', $seekCriteria->getFeeRange()->max)
            ;
        } // END FEE FILTER

        // MV FILTER
        if ($seekCriteria->getMvRange()) {
            $qb
                ->andWhere("t.mv >= :minMv OR :minMv IS NULL")
                ->andWhere("t.mv <= :maxMv OR :maxMv IS NULL")
                ->setParameter('minMv', $seekCriteria->getMvRange()->min)
                ->setParameter('maxMv', $seekCriteria->getMvRange()->max)
            ;
        } // END MV FILTER

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}
