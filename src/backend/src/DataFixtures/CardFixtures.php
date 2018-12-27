<?php

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\Game;
use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CardFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
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
                
                $rows = $this->getGames($limit, $offset);
                $gameUIDs = $playersUIDs = [];

                foreach ($rows as $row) {
                    $gameUIDs[] = $row["gameUID"];
                    foreach ($row["cards"] as $card) {
                        $playersUIDs[] = $card[0];
                    }
                }

                $playerIDsUIDs = $this->em->createQueryBuilder()->select("p.id, p.tmId")->from(Player::class, "p")->where("p.tmId IN (:uids)")->setParameter('uids', $playersUIDs)->getQuery()->getArrayResult();
                $gameIDsUIDs = $this->em->createQueryBuilder()->select("g.id, g.tmId")->from(Game::class, "g")->where("g.tmId IN (:uids)")->setParameter('uids', $gameUIDs)->getQuery()->getArrayResult();

                foreach ($rows as $row) {
                    /** @var Game $game */
                    $game = current(array_filter($gameIDsUIDs, function ($game) use ($row) {return $game["tmId"] == $row["gameUID"];}));
                    if($game) $game = $this->em->getReference(Game::class, $game["id"]);

                    
                    foreach ($row["cards"] as $card) {
                        list($tmId, $time, $reason, $isYellow, $isRed) = $card;

                        /** @var Player $player */
                        $player = current(array_filter($playerIDsUIDs, function ($player) use ($tmId) {return $player["tmId"] == $tmId;}));
                        if($player) $player = $this->em->getReference(Player::class, $player["id"]);

                        switch (true) {
                            case $isYellow && $isRed: $type = 2; break;
                            case $isRed: $type = 1; break;
                            case $isYellow: $type = 0; break;
                            default: $type = false;
                        }

                        if ($type !== false) {
                            $card = (new Card())
                                ->setTime($time)
                                ->setGame($game)
                                ->setType($type)
                            ;
                            if ($player) {
                                $card->setPlayer($player);
                            }
                            if ($reason !== "false") {
                                $card->setReason($reason);
                            }

                            $manager->persist($card);
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
                echo "Time left: " . number_format((microtime(true) - $microtime) / $limit * (850306 - $offset) / 60, 1). "min";
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
        $sql = "SELECT `id`, `gameUID`, `cards` FROM tm.games";

        if ($limit > 0 || $offset > 0)
            $sql .= " LIMIT ${offset}, ${limit}";

        $games = $this->em->getConnection()->executeQuery($sql)->fetchAll();

        $games = array_map(function ($game) {
            $game["cards"] = array_filter(array_map(function ($card) {
                if ($card) {
                    return array_pad(explode("_@", $card), 5, false);
                }
            }, explode('][', $game['cards'])));

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
        return [MainFixtures::class, PlayerNewFixtures::class];
    }
}