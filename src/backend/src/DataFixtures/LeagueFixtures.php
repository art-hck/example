<?php

namespace App\DataFixtures;

use App\Entity\League;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LeagueFixtures extends Fixture implements ContainerAwareInterface
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
    
            $stmt = $em->getConnection()->executeQuery("SELECT `leagueName`, `leagueSeason` FROM `tm`.`games` GROUP BY `leagueName`, `leagueSeason`");

            while ($row = $stmt->fetch()) {
                
                $manager->persist((new League())
                    ->setName($row["leagueName"])
                    ->setSeason($row["leagueSeason"]))
                ;
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