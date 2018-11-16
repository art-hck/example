<?php

namespace App\DataFixtures;

use App\Entity\Assist;
use App\Entity\Game;
use App\Entity\Goal;
use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GoalFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    /** @type ContainerInterface */
    private $container;

    /** @type EntityManager */
    private $em;

    public function load(ObjectManager $manager)
    {
        return;
        echo 123;
        try {
//            $offset = 324000;
            $offset = 0;
            $limit = 2000;
            $microtime = microtime(true);
            while (true) {
                $gameUIDs = $playersUIDs = [];
                $rows = $this->getGames($limit, $offset);

                foreach ($rows as $row) {
                    $gameUIDs[] = $row["gameUID"];
                    foreach ($row["goals"] as $goal) {
                        $playersUIDs[] = $goal[0];
                        $playersUIDs[] = $goal[4];
                    }
                }

                $playerIDsUIDs = $this->em->createQueryBuilder()->select("p.id, p.tmId")->from(Player::class, "p")->where("p.tmId IN (:uids)")->setParameter('uids', $playersUIDs)->getQuery()->getArrayResult();
                $gameIDsUIDs = $this->em->createQueryBuilder()->select("g.id, g.tmId")->from(Game::class, "g")->where("g.tmId IN (:uids)")->setParameter('uids', $gameUIDs)->getQuery()->getArrayResult();

                foreach ($rows as $row) {

                    /** @var Game $game */
                    $game = current(array_filter($gameIDsUIDs, function ($game) use ($row) {
                        return $game["tmId"] == $row["gameUID"];
                    }));
                    
                    if ($game) $game = $this->em->getReference(Game::class, $game["id"]);

                    foreach ($row["goals"] as $goal) {
                        list($tmId, $time, $score, $description, $assistTmId, $assistDescription) = $goal;

                        if ($tmId !== false && $tmId != "false") {
                            /** @var Player $player */
                            $player = current(array_filter($playerIDsUIDs, function ($player) use ($tmId) {
                                return $player["tmId"] == $tmId;
                            }));
                            if ($player) $player = $this->em->getReference(Player::class, $player["id"]);
                            else $player = null;

                            $goal = (new Goal())
                                ->setScore($score)
                                ->setDescription($description)
                                ->setGame($game)
                                ->setTime((int)$time)
                                ->setPlayer($player);;

                            $manager->persist($goal);

                            if ($assistTmId !== false && $assistTmId != "false") {
                                /** @var Player $assistPlayer */
                                $assistPlayer = current(array_filter($playerIDsUIDs, function ($player) use ($assistTmId) {
                                    return $player["tmId"] == $assistTmId;
                                }));

                                if ($assistPlayer) {
                                    $assistPlayer = $this->em->getReference(Player::class, (int)$assistPlayer["id"]);
                                } else $assistPlayer = null;

                                $assist = (new Assist())
                                    ->setDescription($assistDescription)
                                    ->setGoal($goal)
                                    ->setPlayer($assistPlayer);

                                $manager->persist($assist);
                            }
                        }
                    }
                }

                $offset += $limit;

                $manager->flush();
                $manager->clear();
                gc_collect_cycles();
                echo "Offset: ${offset}\t";
                echo "Memory: " . TeamFixtures::convert(memory_get_usage()) . "\t";
                echo "Peak:" . TeamFixtures::convert(memory_get_usage()) . "\t";
                echo "Time: " . time() . "\t";

                echo "Time left: " . number_format((microtime(true) - $microtime) / $offset * 780795 / 60, 1) . "min";
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
    private function getGames($limit = 0, $offset = 0)
    {
        gc_enable();
        $this->em = $this->container->get('doctrine')->getManager();
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $sql = "SELECT `id`, `gameUID`, `goals` FROM tm.games";

        if ($limit > 0 || $offset > 0)
            $sql .= " LIMIT ${offset}, ${limit}";

        $games = $this->em->getConnection()->executeQuery($sql)->fetchAll();

        $games = array_map(function ($game) {
            $game["goals"] = array_filter(array_map(function ($goal) {
                if ($goal) {
                    return array_pad(explode("_@", $goal), 6, false);
                }
            }, explode('][', $game['goals'])));

            return $game;
        }, $games);

        return $games;
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