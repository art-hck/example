<?php

namespace App\DataFixtures;

use App\Entity\Assist;
use App\Entity\Card;
use App\Entity\Game;
use App\Entity\Goal;
use App\Entity\League;
use App\Entity\Player;
use App\Entity\Referee;
use App\Entity\Stadium;
use App\Entity\Team;
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

    public function load(ObjectManager $manager)
    {
        return;
        try {
            gc_enable();

            /** @var EntityManager $em */
            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->getConnection()->getConfiguration()->setSQLLogger(null);

            $stmt = $em->getConnection()->executeQuery("SELECT * FROM tm.games");
            $i = 0;
            while ($row = $stmt->fetch()) {

                $game = $manager->getRepository(Game::class)->findOneBy(["tmId" => $row["gameUID"]]);
    
                foreach(explode('][', $row['cards']) as $item) {
                    if (empty($item)) continue;
                    list($tmId, $time, $reason, $isYellow, $isRed) = array_pad(explode("_@", $item), 5, false);
                    
                    $player = $manager->getRepository(Player::class)->findOneBy(["tmId" => $tmId]);
                    
                    switch(true) {
                        case $isYellow && $isRed: $type = 2; break;
                        case $isRed: $type = 1; break;
                        case $isYellow: $type = 0; break;
                        default: $type = false;
                    }
                    
                    if($type !== false) {
                        $card = (new Card())
                            ->setPlayer($player)
                            ->setTime($time)
                            ->setGame($game)
                            ->setType($type);
    
                        if ($reason !== "false") {
                            $card->setReason($reason);
                        }
    
                        $manager->persist($card);
                    }
                }

                if(++$i % 1000 == 0) {
                    echo "Cleaning......." . PHP_EOL;
                    $manager->flush();
                    $manager->clear();
                    gc_collect_cycles();
                }

                echo "Index: " . $i . "\trowId:" . $row["id"] . "\t";
                echo TeamFixtures::convert(memory_get_usage()) . PHP_EOL;
            }


            $manager->flush();
            $manager->clear();

        } catch (DBALException $e) {

            die($e->getMessage());
        }

    }

    public function setContainer( ContainerInterface $container = null )
    {
        $this->container = $container;
    }

    public function getDependencies(): array
    {
        return [Game::class];
    }
}