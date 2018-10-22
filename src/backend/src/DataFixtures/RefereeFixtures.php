<?php

namespace App\DataFixtures;

use App\Entity\Referee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RefereeFixtures extends Fixture implements ContainerAwareInterface
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
                SELECT `refereeName`, `refereeUID` 
                FROM `tm`.`games` 
                WHERE `refereeUID` > 0
                GROUP BY `refereeUID`, `refereeName`
            ");

            while ($row = $stmt->fetch()) {
                $manager->persist((new Referee())
                    ->setName($row["refereeName"])
                    ->setTmId($row["refereeUID"])
                );
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