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
        try {
            $offset = 0;
            $limit = 5000;
            while (true) {
                $gameIds = $playersIds = [];
                $games = $this->getGames($limit, $offset);

                foreach ($games as $game) {
                    $gameIds[] = $game["gameUID"];
                    foreach (array_merge($games[0]["awayTeamPlayers"], $games[0]["homeTeamPlayers"]) as $player) {
                        $playersIds[] = $player[0];
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

                    foreach (array_merge($game["awayTeamPlayers"], $game["homeTeamPlayers"]) as $substitution) {
                        list($tmId, $name, $enterTime, $outTime) = $substitution;
                        
                        if($tmId !== false && $tmId!="false" && $enterTime && $outTime) {
                            /** @var Player[] $playerEntity */
                            $playerEntity = array_filter($playerEntities, function (Player $playerEntity) use ($tmId) {
                                return $playerEntity->getTmId() == $tmId;
                            });

                            $playerEntity = array_shift($playerEntity);

                            $substitution = (new Substitution())
                                ->setGame($gameEntity)
                                ->setJoinTime($enterTime)
                                ->setPlayTime($outTime)
                            ;
                            
                            if($playerEntity) {
                                $substitution->setPlayer($playerEntity);
                            }

                            $manager->persist($substitution);

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