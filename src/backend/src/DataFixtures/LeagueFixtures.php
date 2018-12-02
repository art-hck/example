<?php

namespace App\DataFixtures;

use App\Entity\League;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LeagueFixtures extends Fixture implements ContainerAwareInterface
{
    /** @type ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
        return;
        $offset = 0;
        $limit = 1000;
        while (true) {
            $rows = $this->getGames($limit, $offset);
            foreach($rows as $row) {
                $isInt = preg_match("/^(World Cup|Confederations|König-Fahd-Pokal|International|CONCACAF|Artemio)/i", $row["leagueName"]);
                
                $manager->persist((new League())
                    ->setName($row["leagueName"])
                    ->setSeason($row["leagueSeason"])
                    ->setIsInternational($isInt)
                );
            }
            $offset += $limit;
            
            $manager->flush();
            $manager->clear();
            gc_collect_cycles();
            echo "Offset: ${offset}\tMemory: " . TeamFixtures::convert(memory_get_usage()) . "\tPeak:" . TeamFixtures::convert(memory_get_usage()) . "\tTime: " . time() . PHP_EOL;
            if (count($rows) < $limit) break;
        }
    }


    private function getGames($limit = 0, $offset = 0)
    {
        gc_enable();
        $this->em = $this->container->get('doctrine')->getManager();
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $sql = "SELECT `leagueName`, `leagueSeason` FROM `tm`.`games` GROUP BY `leagueName`, `leagueSeason`";

        if ($limit > 0 || $offset > 0)
            $sql .= " LIMIT ${offset}, ${limit}";

        return $this->em->getConnection()->executeQuery($sql)->fetchAll();
    }    

    public function setContainer( ContainerInterface $container = null )
    {
        $this->container = $container;
    }
}