<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Substitution;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SubstitutionFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    /** @type ContainerInterface */
    private $container;

    /** @type EntityManager */
    private $em;

    public function load(ObjectManager $manager)
    {
        //return;
        try {
            $offset = 200000;
            $limit = 500;
            $end = 10000000;
            $end += $offset;
            
            $microtime = microtime(true);
            
            while (true) {
                $cycleMicrotime = microtime(true);
                
                $gameUIDs = $playersUIDs = [];
                $rows = $this->getGames($limit, $offset);

                foreach ($rows as $i => $row) {
                    $gameUIDs[] = $row["gameUID"];
                    foreach (array_merge($row["awayTeamPlayers"], $row["homeTeamPlayers"]) as $player) {
                        $playersUIDs[] = $player[0];
                    }
                }
                $gameUIDs = array_unique($gameUIDs);
                $playersUIDs = array_unique($playersUIDs);
                
                $playerIDsUIDs = $this->em->createQueryBuilder()->select("p.id, p.tmId")->from(Player::class, "p")->where("p.tmId IN (:uids)")->setParameter('uids', $playersUIDs)->getQuery()->getArrayResult();
                $gameIDsUIDs = $this->em->createQueryBuilder()->select("g.id, g.tmId")->from(Game::class, "g")->where("g.tmId IN (:uids)")->setParameter('uids', $gameUIDs)->getQuery()->getArrayResult();

                foreach ($rows as $row) {

                    /** @var Game $game */
                    $game = current(array_filter($gameIDsUIDs, function ($game) use ($row) {return $game["tmId"] == $row["gameUID"];}));
                    if($game) $game = $this->em->getReference(Game::class, $game["id"]);

                    foreach (array_merge($row["awayTeamPlayers"], $row["homeTeamPlayers"]) as $substitution) {
                        list($tmId, $name, $enterTime, $outTime) = $substitution;

                        if ($tmId !== false && $tmId != "false" && $enterTime && $outTime) {
                            /** @var Player $player */
                            $player = current(array_filter($playerIDsUIDs, function ($player) use ($tmId) {return $player["tmId"] == $tmId;}));
                            if($player) $player = $this->em->getReference(Player::class, $player["id"]);
                            else $player = null;

                            $substitution = (new Substitution())
                                ->setGame($game)
                                ->setJoinTime((int)$enterTime)
                                ->setPlayTime((int)$outTime)
                                ->setPlayer($player)
                            ;
                            
                            $manager->persist($substitution);

                        }
                    }
                }

                $offset += $limit;

                $manager->flush();
                $manager->clear();
                gc_collect_cycles();
                
                $time = \DateTime::createFromFormat('U.u', microtime(TRUE));
                if($time) $time = $time->format("H:i:s.u");
                echo "Offset: ${offset}\t";
                echo "Memory: " . TeamFixtures::convert(memory_get_usage()) . "\t";
//                echo "Peak:" . TeamFixtures::convert(memory_get_peak_usage()) . "\t";
                echo "Time: " . $time . "\t";
                echo "Time left: " . number_format((microtime(true) - $microtime) / $offset * 780795 / 60, 1) . "min\t";
                echo "Cycle time: " . number_format((microtime(true) - $cycleMicrotime), 3) . "sec\t";
                echo PHP_EOL;

                if (count($rows) < $limit) break;
                if($offset >= $end) break;
            }

            $manager->flush();
            $manager->clear();

        } catch (DBALException $e) {
            die($e->getMessage());
        } catch (\Exception $e) {
            die($e->getMessage());
        }

    }

    /**
     * @param $limit
     * @param $offset
     * @return array
     * @throws DBALException
     */
    private function getGames($limit = 0, $offset = 0)
    {
        gc_enable();
        $this->em = $this->container->get('doctrine')->getManager();
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $sql = "SELECT `id`, `gameUID`, `homeTeamPlayers`, `awayTeamPlayers` FROM tm.games";

        if ($limit > 0 || $offset > 0)
            $sql .= " LIMIT ${offset}, ${limit}";

        $games = $this->em->getConnection()->executeQuery($sql)->fetchAll();

        $games = array_map(function ($game) {
            $game["homeTeamPlayers"] = array_filter(array_map(function ($player) {
                if ($player) {
                    return array_pad(explode("_@", $player), 4, false);
                }
            }, explode('][', $game['homeTeamPlayers'])));
            
            $game["awayTeamPlayers"] = array_filter(array_map(function ($player) {
                if ($player) {
                    return array_pad(explode("_@", $player), 4, false);
                }
            }, explode('][', $game['awayTeamPlayers'])));

            return $game;
        }, $games);

        return $games;
    }

    public function setContainer( ContainerInterface $container = null )
    {
        $this->container = $container;
    }

    public function getDependencies(): array
    {
        return [MainFixtures::class];
    }
}