<?php

namespace App\DataFixtures;

use App\Entity\Stadium;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StadiumFixtures extends Fixture implements ContainerAwareInterface
{
    /** @type ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
        return;
        try {
            gc_enable();
        
            /** @var EntityManager $em */
            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->getConnection()->getConfiguration()->setSQLLogger(null);
            
            $stmt = $em->getConnection()->executeQuery("
               SELECT
                  `stadiumName`,
                  `stadiumUID`
                FROM
                  `tm`.`games`
                WHERE id IN (
                    SELECT MAX(id)
                    FROM `tm`.`games`
                    GROUP BY `stadiumUID`
                    
                ) AND `stadiumUID`>0
                ORDER BY `stadiumUID`
            ");

            while ($row = $stmt->fetch()) {
                $manager->persist((new Stadium())
                    ->setName($row["stadiumName"])
                    ->setTmId($row["stadiumUID"]));
            }
    
            $manager->flush();
            $manager->clear();
            
            gc_collect_cycles();
        } catch (DBALException $e) {}
    }

    public function setContainer( ContainerInterface $container = null )
    {
        $this->container = $container;
    }
}