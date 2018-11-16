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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MainFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
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
                $rows = $this->getGames($limit, $offset);
                $leaguesData = $teamUIDS = $refereeUIDS = $stadiumUIDS = [];

                foreach($rows as $row) { // Захуячиваем массивы UID
                    $stadiumUIDS[] = $row["stadiumUID"];
                    $refereeUIDS[] = $row["refereeUID"];
                    $teamUIDS[] = $row["homeTeamUID"];
                    $teamUIDS[] = $row["awayTeamUID"];
                    $leaguesData[] = ["name" => $row["leagueName"], "season" => $row["leagueSeason"]]; // у лиг нет uid по этому по ищем имени-сезону
                }
    
                // Получаем массивы [ID, UID] для последующего получения ID элементов по UID
                $stadiumID_UID = $this->em->createQueryBuilder()->select("s.id, s.tmId")->from(Stadium::class, "s")->where("s.tmId IN (:uids)")->setParameter('uids', $stadiumUIDS)->getQuery()->getArrayResult();
                $refereeID_UID = $this->em->createQueryBuilder()->select("r.id, r.tmId")->from(Referee::class, "r")->where("r.tmId IN (:uids)")->setParameter('uids', $refereeUIDS)->getQuery()->getArrayResult();
                $teamID_UID = $this->em->createQueryBuilder()->select("t.id, t.tmId")->from(Team::class, "t")->where("t.tmId IN (:uids)")->setParameter('uids', $teamUIDS)->getQuery()->getArrayResult();
                $leaguesNAME_SEASON = $this->em->createQueryBuilder()->select("l")->from(League::class, "l")->where("l.name IN (:name)")->andWhere("l.season IN (:season)")->setParameter('name', array_unique(array_column($leaguesData, "name")))->setParameter('season', array_unique(array_column($leaguesData, "season")))->getQuery()->getArrayResult();            
    
                foreach($rows as $row) {
                    
    
                    // Находим в массивах [ID,UID] по UID (в случае с лигой ищем по имени и сезону)
                    $league    = current(array_filter($leaguesNAME_SEASON,  function ($league)  use ($row) { return $league["name"]  == $row["leagueName"] && $league["season"] == $row["leagueSeason"];}));
                    $stadium   = current(array_filter($stadiumID_UID,       function ($stadium) use ($row) { return $stadium["tmId"] == $row["stadiumUID"]; }));
                    $referee   = current(array_filter($refereeID_UID,       function ($referee) use ($row) { return $referee["tmId"] == $row["refereeUID"]; }));
                    $homeTeam  = current(array_filter($teamID_UID,          function ($team)    use ($row) { return $team["tmId"]    == $row["homeTeamUID"];}));
                    $guestTeam = current(array_filter($teamID_UID,          function ($team)    use ($row) { return $team["tmId"]    == $row["awayTeamUID"];}));

                    /** @var League $league */
                    if($league) $league = $this->em->getReference(League::class, $league["id"]);
                    /** @var Stadium $stadium */
                    if($stadium) $stadium = $this->em->getReference(Stadium::class, $stadium["id"]);
                    /** @var Referee $referee */
                    if($referee) $referee = $this->em->getReference(Referee::class, $referee["id"]);
                    /** @var Team $homeTeam */
                    if($homeTeam) $homeTeam = $this->em->getReference(Team::class, $homeTeam["id"]);
                    /** @var Team $guestTeam */
                    if($guestTeam) $guestTeam = $this->em->getReference(Team::class, $guestTeam["id"]);

                    $game = (new Game())
                        ->setTmId($row["gameUID"])
                        ->setScore($row["score"])
                        ->setDate(new \DateTime("@" . $row["datestamp"]))
                        ->setDay($row["matchDay"])
                        ->setDuration($row["duration"])
                        ->setStatus($row["status"])
                        ->setUpdated(new \DateTime("@" . $row["updated"]))
                        ->setLeague($league)
                        ->setStadium($stadium)
                        ->setReferee($referee)
                    ;
    
                    $manager->persist((new TeamGame())->setType(1)->setGame($game)->setTeam($homeTeam));
                    $manager->persist((new TeamGame())->setType(2)->setGame($game)->setTeam($guestTeam));

                    if ($row["stadiumAttendance"] !== "false") $game->setAttendance((float)$row["stadiumAttendance"]);

                    $manager->persist($game);
                }
    
                $offset += $limit;
    
                $manager->flush();
                $manager->clear();
                gc_collect_cycles();
                echo "Offset: ${offset}\tMemory: " . TeamFixtures::convert(memory_get_usage()) . "\tPeak:" . TeamFixtures::convert(memory_get_usage()) . "\tTime: " . time() . PHP_EOL;
                if (count($rows) < $limit) break;
            }

            $manager->flush();
            $manager->clear();

        } catch (ORMException $e) {
            die($e->getMessage());
        }
    }

    private function getGames($limit = 0, $offset = 0)
    {
        gc_enable();
        $this->em = $this->container->get('doctrine')->getManager();
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $sql = "SELECT leagueName, leagueSeason, stadiumUID, refereeUID, homeTeamUID, awayTeamUID, gameUID, score, datestamp, matchDay, duration, status, updated, stadiumAttendance FROM tm.games";

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
        return [LeagueFixtures::class, StadiumFixtures::class, RefereeFixtures::class];
    }
}