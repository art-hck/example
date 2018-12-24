<?php

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Team;
use App\Entity\Transfer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TransferFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
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
                
                $rows = $this->getTransfers($limit, $offset);
                $teamUIDs = $playersUIDs = [];

                foreach ($rows as $row) {
                    $teamUIDs[] = $row["left_uid"];
                    $teamUIDs[] = $row["join_uid"];
                    $playersUIDs[] = $row["player_uid"];
                }

                $playerIDsUIDs = $this->em->createQueryBuilder()->select("p.id", "p.tmId")->from(Player::class, "p")->where("p.tmId IN (:uids)")->setParameter('uids', $playersUIDs)->getQuery()->getArrayResult();
                $teamIDsUIDs = $this->em->createQueryBuilder()->select("t.id", "t.tmId")->from(Team::class, "t")->where("t.tmId IN (:uids)")->setParameter('uids', $teamUIDs)->getQuery()->getArrayResult();

                foreach ($rows as $row) {
                    /** @var Team $join_team */
                    $join_team = current(array_filter($teamIDsUIDs, function ($team) use ($row) {return $team["tmId"] == $row["join_uid"];}));
                    if($join_team) $join_team = $this->em->getReference(Team::class, $join_team["id"]);

                    /** @var Team $left_team */
                    $left_team = current(array_filter($teamIDsUIDs, function ($team) use ($row) {return $team["tmId"] == $row["left_uid"];}));
                    if($left_team) $left_team = $this->em->getReference(Team::class, $left_team["id"]);

                    /** @var Player $player */
                    $player = current(array_filter($playerIDsUIDs, function ($player) use ($row) {return $player["tmId"] == $row["player_uid"];}));
                    if($player) $player = $this->em->getReference(Player::class, $player["id"]);
                    
                    $transfer = new Transfer();
                    
                    if($join_team) {
                        $transfer->setJoinTeam($join_team);
                    }
                    
                    if($left_team) {
                        $transfer->setLeftTeam($left_team);
                    }
                    
                    if($player) {
                        $transfer->setPlayer($player);
                    }

                    if($row["date"]) {
                        $transfer->setDate(new \DateTime($row["date"]));
                    }

                    if($row["fee_sum"] > 0) {
                        $transfer->setFee($row["fee_sum"]);
                    }
                    
                    if($row["mv_sum"] > 0) {
                        $transfer->setMv($row["mv_sum"]);
                    }
                    
                    $manager->persist($transfer);
                }

                $offset += $limit;

                $manager->flush();
                $manager->clear();
                
                gc_collect_cycles();

                echo "Offset: ${offset}\t";
                echo "Memory: " . TeamFixtures::convert(memory_get_usage()) . "\t";
                echo "Peak:" . TeamFixtures::convert(memory_get_usage()) . "\t";
                echo "Time: " . time() . "\t";
                echo "Time left: " . number_format((microtime(true) - $microtime) / $limit * (797410 - $offset) / 60, 1). "min";
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
    private function getTransfers($limit = 0, $offset = 0)
    {
        gc_enable();
        $this->em = $this->container->get('doctrine')->getManager();
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $sql = "SELECT `id`, `date`, `left_uid`, `join_uid`, `mv_sum`, `fee_sum`, `player_uid` FROM tm.transfer";
        
        if ($limit > 0 || $offset > 0)
            $sql .= " LIMIT ${offset}, ${limit}";

        return $this->em->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getDependencies(): array
    {
        return [TeamFixtures::class, PlayerFixtures::class];
    }
}