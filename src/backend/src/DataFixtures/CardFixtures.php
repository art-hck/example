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
            $limit = 5000;
            while (true) {
                $gameIds = $playersIds = [];
                $games = $this->getGames($limit, $offset);

                foreach ($games as $game) {
                    $gameIds[] = $game["gameUID"];
                    foreach ($game["cards"] as $card) {
                        $playersIds[] = $card[0];
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

                    foreach ($game["cards"] as $card) {
                        list($tmId, $time, $reason, $isYellow, $isRed) = $card;

                        /** @var Player[] $playerEntity */
                        $playerEntity = array_filter($playerEntities, function (Player $playerEntity) use ($tmId) {
                            return $playerEntity->getTmId() == $tmId;
                        });

                        $playerEntity = array_shift($playerEntity);

                        switch (true) {
                            case $isYellow && $isRed: $type = 2; break;
                            case $isRed: $type = 1; break;
                            case $isYellow: $type = 0; break;
                            default: $type = false;
                        }

                        if ($type !== false) {
                            $card = (new Card())
                                ->setTime($time)
                                ->setGame($gameEntity)
                                ->setType($type)
                            ;
                            if ($playerEntity) {
                                $card->setPlayer($playerEntity);
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
                echo "Offset ${offset}\t" . TeamFixtures::convert(memory_get_usage()) . PHP_EOL;

                if (count($games) < $limit) break;
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
        return [MainFixtures::class];
    }
}