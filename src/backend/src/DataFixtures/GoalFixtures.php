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

class GoalFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
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

                $game = $manager->getRepository(Game::class)->findOneBy([
                    "tmId" => $row["gameUID"],
                ]);

                foreach(explode('][', $row['goals']) as $item) {
                    if(empty($item)) continue;
                    
                    list($tmId, $time, $score, $description, $assistTmId, $assistDescription) = array_pad(explode("_@", $item), 6, false);
                    
                    if($tmId !== false && $tmId!="false") {
                        $player = $manager->getRepository(Player::class)->findOneBy(["tmId" => $tmId]);
                        if($player) {
                            $goal = (new Goal())
                                ->setPlayer($player)
                                ->setScore($score)
                                ->setDescription($description)
                                ->setGame($game)
                                ->setTime($time)
                            ;

                            $manager->persist($goal);
    
                            if($assistTmId !== false && $assistTmId!="false") {
                                $assistant = $manager->getRepository(Player::class)->findOneBy(["tmId" =>  $assistTmId]);
                                if($assistant) {
                                    $assist = (new Assist())
                                        ->setDescription($assistDescription)
                                        ->setPlayer($assistant)
                                        ->setGoal($goal)
                                    ;
                                    $manager->persist($assist);
                                }
                            }
                            }
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
//                unset($row, $league, $stadium, $referee, $homeTeam, $guestTeam, $game);
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