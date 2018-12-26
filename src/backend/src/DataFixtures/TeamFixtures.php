<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TeamFixtures extends Fixture implements ContainerAwareInterface
{
    /** @type EntityManager */
    private $em;
    /** @type ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
//        return;
        $limit = 1000;
        $offset = 0;
        try {
            while (true) {
                $microtime = microtime(true);

                $rows = $this->getData($limit, $offset);

                foreach ($rows as $row) {
                    if (empty($row["uid"]) || empty($row["name"]) || empty($row["alias"])) continue;

                    $team = (new Team())
                        ->setTmId($row["uid"])
                        ->setName($row["name"])
                        ->setAlias($row["alias"])
                        //->setLeague($row[""])
                        ->setCreated(new \DateTime("@" . $row["created"]))
                        ->setUpdated(new \DateTime("@" . $row["updated"]));

                    if (!empty($row["preview"]))
                        $team->setPreview(str_replace("https://tmssl.akamaized.net/", "", $row["preview"]));

                    $manager->persist($team);
                }
                $offset += $limit;

                $manager->flush();
                $manager->clear();
                gc_collect_cycles();

                echo "Offset: ${offset}\t";
                echo "Memory: " . TeamFixtures::convert(memory_get_usage()) . "\t";
                echo "Peak:" . TeamFixtures::convert(memory_get_usage()) . "\t";
                echo "Time: " . time() . "\t";
                echo "Time left: " . number_format((microtime(true) - $microtime) / $limit * (27019 - $offset) / 60, 1) . "min";
                echo PHP_EOL;

                if (count($rows) < $limit) break;
            }

            $manager->flush();
            $manager->clear();
            gc_collect_cycles();
            
        } catch (\Exception $e) {
            dump($e);
        }
    }


    public function getData($limit = 0, $offset = 0)
    {
        gc_enable();
        $this->em = $this->em = $this->container->get('doctrine')->getManager();
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $sql = "SELECT * FROM tm.builder_team";

        if ($limit > 0 || $offset > 0)
            $sql .= " LIMIT ${offset}, ${limit}";

        return $this->em->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public static function convert($size)
    {
        return @round($size / pow(1024, ($j = (int)floor(log($size, 1024)))), 2) . ' ' . ['b', 'kb', 'mb', 'gb', 'tb', 'pb'][$j];
    }

    public function getDependencies(): array
    {
        return [LeagueFixtures::class];
    }    
}