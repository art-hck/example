<?php

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\Country;
use App\Entity\Game;
use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CountryFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    /** @type ContainerInterface */
    private $container;
    /** @type EntityManager */
    private $em;

    public function load(ObjectManager $manager)
    {
        return;
        try {
            $offset = 0;
            $limit = 1000;
            while (true) {
                $microtime = microtime(true);
                
                $rows = $this->getResult($limit, $offset);

                foreach ($rows as $row) {
                    $manager->persist((new Country())
                        ->setName($row["name"])
                        ->setAlias($row["alias"]));
                }

                $offset += $limit;

                $manager->flush();
                $manager->clear();
                gc_collect_cycles();

                echo "Offset: ${offset}\t";
                echo "Memory: " . TeamFixtures::convert(memory_get_usage()) . "\t";
                echo "Peak:" . TeamFixtures::convert(memory_get_usage()) . "\t";
                echo "Time: " . time() . "\t";
                echo "Time left: " . number_format((microtime(true) - $microtime) / $limit * (780795 - $offset) / 60, 1). "min";
                echo PHP_EOL;

                if (count($rows) < $limit) break;
            }

            $manager->flush();
            $manager->clear();

        } catch (DBALException $e) {
            die($e->getMessage());
        }

    }

    /**
     * @param $limit
     * @param $offset
     * @return array
     * @throws DBALException
     */
    private function getResult($limit = 0, $offset = 0)
    {
        gc_enable();
        $this->em = $this->container->get('doctrine')->getManager();
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $sql = "SELECT `id`, `name`, `alias` FROM tm.country";

        if ($limit > 0 || $offset > 0)
            $sql .= " LIMIT ${offset}, ${limit}";

        $result = $this->em->getConnection()->executeQuery($sql)->fetchAll();

        return $result;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getDependencies(): array
    {
        return [MainFixtures::class];
    }
}