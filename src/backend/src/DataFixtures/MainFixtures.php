<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\League;
use App\Entity\Referee;
use App\Entity\Stadium;
use App\Entity\Team;
use App\Entity\TeamGame;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MainFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    /** @type ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
//        return;
        try {
            gc_enable();

            /** @var EntityManager $em */
            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->getConnection()->getConfiguration()->setSQLLogger(null);

            $stmt = $em->getConnection()->executeQuery("SELECT * FROM tm.games");
            $i = 0;
            while ($row = $stmt->fetch()) {

                $league = $manager->getRepository(League::class)->findOneBy([
                    "name" => $row["leagueName"],
                    "season" => $row["leagueSeason"],
                ]);

                $stadium = $manager->getRepository(Stadium::class)->findOneBy(["tmId" => $row["stadiumUID"]]);
                $referee = $manager->getRepository(Referee::class)->findOneBy(["tmId" => $row["refereeUID"]]);
                $homeTeam = $manager->getRepository(Team::class)->findOneBy(["tmId" => $row["homeTeamUID"]]);
                $guestTeam = $manager->getRepository(Team::class)->findOneBy(["tmId" => $row["awayTeamUID"]]);
                $game = (new Game())
                    ->setTmId($row["gameUID"])
                    ->setStadium($stadium)
                    ->setReferee($referee)
                    ->setLeague($league)
                    ->setScore($row["score"])
                    ->setDate(new \DateTime("@" . $row["datestamp"]))
                    ->setDay($row["matchDay"])
                    ->setDuration($row["duration"])
                    ->setStatus($row["status"])
                    ->setUpdated(new \DateTime("@" . $row["updated"]))
                ;

                $homeTeamGame = (new TeamGame())->setType(1)->setGame($game)->setTeam($homeTeam);
                $guestTeamGame = (new TeamGame())->setType(2)->setGame($game)->setTeam($guestTeam);

                if ($row["stadiumAttendance"] !== "false")
                    $game->setAttendance((float)$row["stadiumAttendance"]);

                $manager->persist($game);
                $manager->persist($homeTeamGame);
                $manager->persist($guestTeamGame);

                if (++$i % 1000 == 0) {
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

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getDependencies(): array
    {
        return [LeagueFixtures::class, StadiumFixtures::class, RefereeFixtures::class];
    }
}