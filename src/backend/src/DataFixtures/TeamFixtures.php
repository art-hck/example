<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TeamFixtures extends Fixture implements ContainerAwareInterface
{
    /** @type ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
        try {
            gc_enable();

            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->getConnection()->getConfiguration()->setSQLLogger(null);

            $stmt = $em->getConnection()->executeQuery("SELECT * FROM player.builder_team");

            $i = 0;
            while ($row = $stmt->fetch()) {
    
                if (empty($row["uid"]) || empty($row["name"]) || empty($row["alias"])) continue;
                
                $team = (new Team())
                    ->setTmId($row["uid"])
                    ->setName($row["name"])
                    ->setAlias($row["alias"])
                    ->setCreated(new \DateTime("@" . $row["created"]))
                    ->setUpdated(new \DateTime("@" . $row["updated"]))
                ;
                
                if (!empty($row["preview"]))
                    $team->setPreview(str_replace("https://tmssl.akamaized.net/", "", $row["preview"]));
    
                $manager->persist($team);
    
                if ($i++ > 10000) {
                    $i = 0;
                    $manager->flush();
                    $manager->clear();
                    gc_collect_cycles();
                }
            }
        } catch (DBALException $e) {}

        $manager->flush();
        $manager->clear();
        gc_collect_cycles();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    private static function convert($size)
    {
        return @round($size / pow(1024, ($j = (int)floor(log($size, 1024)))), 2) . ' ' . ['b', 'kb', 'mb', 'gb', 'tb', 'pb'][$j];
    }
}