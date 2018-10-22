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
        try {
            $offset = 0;
            $limit = 5000;
            while (true) {
                $gameIds = $playersIds = [];
                $games = $this->getGames($limit, $offset);

                foreach ($games as $game) {
                    $gameIds[] = $game["gameUID"];
                    foreach ($game["goals"] as $goal) {
                        $playersIds[] = $goal[0];
                        $playersIds[] = $goal[4];
                    }
                }

                $playerEntities = $manager->getRepository(Player::class)->findByIds($playersIds);
                $gameEntities = $manager->getRepository(Game::class)->findByIds($gameIds);

                foreach ($games as $game) {

                    /** @var Game[] $gameEntity */
                    $gameEntity = array_filter($gameEntities, function (Game $gameEntity) use ($game) {
                        return $gameEntity->getTmId() === (int)$game["gameUID"];
                    });

                    $gameEntity = array_shift($gameEntity);

                    foreach ($game["goals"] as $goal) {
                        list($tmId, $time, $score, $description, $assistTmId, $assistDescription) = $goal;

                        if($tmId !== false && $tmId!="false") {
                            /** @var Player[] $playerEntity */
                            $playerEntity = array_filter($playerEntities, function (Player $playerEntity) use ($tmId) {
                                return $playerEntity->getTmId() == $tmId;
                            });

                            $playerEntity = array_shift($playerEntity);
                            
                            $goal = (new Goal())
                                ->setScore($score)
                                ->setDescription($description)
                                ->setGame($gameEntity)
                                ->setTime($time)
                            ;
                            if($playerEntity)
                                $goal->setPlayer($playerEntity);

                            $manager->persist($goal);

                            if($assistTmId !== false && $assistTmId!="false") {
                                $assistEntity = array_filter($playerEntities, function (Player $playerEntity) use ($tmId) {
                                    return $playerEntity->getTmId() == $tmId;
                                });

                                $assistEntity = array_shift($assistEntity);
                                $assist = (new Assist())
                                    ->setDescription($assistDescription)
                                    ->setGoal($goal)
                                ;
                                if($assistEntity)
                                    $assist->setPlayer($assistEntity);
                                
                                $manager->persist($assist);
                            }
                            
                        }                        
                    }
                }

                $offset += $limit;

                $manager->flush();
                $manager->clear();
                gc_collect_cycles();
                echo "Offset ${offset}\t" . TeamFixtures::convert(memory_get_usage()) . PHP_EOL;

                if (count($games) < $limit) break;
            }

            $manager->flush();
            $manager->clear();

        } catch (DBALException $e) {
            die($e->getMessage());
        }

    }
    
//    public function load2(ObjectManager $manager)
//    {
//        return;
//        try {
//            gc_enable();
//
//            /** @var EntityManager $em */
//            $em = $this->container->get('doctrine.orm.entity_manager');
//            $em->getConnection()->getConfiguration()->setSQLLogger(null);
//
//            $stmt = $em->getConnection()->executeQuery("SELECT * FROM tm.games");
//            $i = 0;
//            while ($row = $stmt->fetch()) {
//
//                $game = $manager->getRepository(Game::class)->findOneBy([
//                    "tmId" => $row["gameUID"],
//                ]);
//
//                foreach(explode('][', $row['goals']) as $item) {
//                    if(empty($item)) continue;
//                    
//                    list($tmId, $time, $score, $description, $assistTmId, $assistDescription) = array_pad(explode("_@", $item), 6, false);
//                    
//                    if($tmId !== false && $tmId!="false") {
//                        $player = $manager->getRepository(Player::class)->findOneBy(["tmId" => $tmId]);
//                        if($player) {
//                            $goal = (new Goal())
//                                ->setPlayer($player)
//                                ->setScore($score)
//                                ->setDescription($description)
//                                ->setGame($game)
//                                ->setTime($time)
//                            ;
//
//                            $manager->persist($goal);
//    
//                            if($assistTmId !== false && $assistTmId!="false") {
//                                $assistant = $manager->getRepository(Player::class)->findOneBy(["tmId" =>  $assistTmId]);
//                                if($assistant) {
//                                    $assist = (new Assist())
//                                        ->setDescription($assistDescription)
//                                        ->setPlayer($assistant)
//                                        ->setGoal($goal)
//                                    ;
//                                    $manager->persist($assist);
//                                }
//                            }
//                            }
//                    }
//                    
//                }
//
//                if(++$i % 1000 == 0) {
//                    echo "Cleaning......." . PHP_EOL;
//                    $manager->flush();
//                    $manager->clear();
//                    gc_collect_cycles();
//                }
//
//                echo "Index: " . $i . "\trowId:" . $row["id"] . "\t";
//                echo TeamFixtures::convert(memory_get_usage()) . PHP_EOL;
////                unset($row, $league, $stadium, $referee, $homeTeam, $guestTeam, $game);
//            }
//
//
//            $manager->flush();
//            $manager->clear();
//
//        } catch (DBALException $e) {
//
//            die($e->getMessage());
//        }
//
//    }


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
    
    public function setContainer( ContainerInterface $container = null )
    {
        $this->container = $container;
    }

    public function getDependencies(): array
    {
        return [MainFixtures::class];
    }
}