<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Player;
use App\Entity\Team;
use App\Type\PlayerRole\PlayerRoleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PlayerNewFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    /** @type EntityManager */
    private $em;
    /** @type ContainerInterface */
    private $container;
    
    public function load(ObjectManager $manager)
    {
        return;
        try {
            $limit = 1000;
            $offset = 0;
            while (true) {
                $microtime = microtime(true);

                $rows = $this->getData($limit, $offset);

                foreach ($rows as $row) {
                    $teamUIDs[] = $row["clubUID"];
                }

                $teamIDsUIDs = $this->em->createQueryBuilder()->select("t.id, t.tmId")->from(Team::class, "t")->where("t.tmId IN (:uids)")
                    ->setParameter('uids', $teamUIDs)->getQuery()->getArrayResult();

                foreach ($rows as $row) {
                    if (empty($row["lastName"]) || empty($row["playerUID"]) || empty($row["alias"])) continue;
                    

                    $player = (new Player())
                        ->setLastName($row["lastName"])
                        ->setTmId($row["playerUID"])
                        ->setAlias($row["alias"]);

                    if (!empty($row["firstName"])) $player->setFirstName($row["firstName"]);
                    if (!empty($row["nativeName"])) $player->setNativeName($row["nativeName"]);
                    if (!empty($row["birthdaystamp"])) $player->setBirthday(new \DateTime("@" . $row["birthdaystamp"]));
                    if (!empty($row["birthplace"])) $player->setBirthPlace($row["birthplace"]);
                    if (!empty($row["birthcountryUID"])) $player->setBirthCountry($this->em->getReference(Country::class, $row["birthcountryUID"]));
                    if (!empty($row["nationalityUID"])) $player->setCountry($this->em->getReference(Country::class, $row["nationalityUID"]));

                    if (!empty($row["foot"])) $player->setFoot($row["foot"]);
                    if (!empty($row["position"])) {
                        $row["position"] = explode(" - ", $row["position"]); 
                        $player->setRole(PlayerRoleFactory::createFromString(trim(array_pop($row["position"]))));
                    }
                    if (!empty($row["height"])) $player->setHeight($row["height"]);
                    if (!empty($row["playerNumber"])) $player->setNumber($row["playerNumber"]);
                    if (!empty($row["playerImg"])) $player->setAvatar(str_replace("https://tmssl.akamaized.net/", "", $row["playerImg"]));

                    if (!empty($row["clubUID"])) {
                        /** @var Team $team */
                        $team = current(array_filter($teamIDsUIDs, function ($team) use ($row) { return $team["tmId"] == $row["clubUID"];}));
                        if($team) $team = $this->em->getReference(Team::class, $team["id"]);
                        else $team = null;
                        
                        $player->setTeam($team);
                    }
                    
                    if (!empty($row["joined"]) && $row["joined"] != "0000-00-00"){
                        $player->setJoined(new \DateTime($row["joined"]));  
                    }
                    
                    if (!empty($row["until"]) && $row["until"] != "0000-00-00") {
                        $player->setUntil(new \DateTime($row["until"]));
                    }
                    
                    if (!empty($row["twitter"])) $player->setTwitter($row["twitter"]);
                    if (!empty($row["facebook"])) $player->setFacebook($row["facebook"]);
                    if (!empty($row["instagram"])) $player->setInstagram($row["instagram"]);

                    $this->em->persist($player);
                }

                $offset += $limit;

                $manager->flush();
                $manager->clear();
                gc_collect_cycles();

                echo "Offset: ${offset}\t";
                echo "Memory: " . TeamFixtures::convert(memory_get_usage()) . "\t";
                echo "Peak:" . TeamFixtures::convert(memory_get_usage()) . "\t";
                echo "Time: " . time() . "\t";
                echo "Time left: " . number_format((microtime(true) - $microtime) / $limit * (166836 - $offset) / 60, 1). "min";
                echo PHP_EOL;

                if (count($rows) < $limit) break;
            }
        } catch (ORMException $e) {
            dump($e);
            dump($row);
        }
    }
    
    public function getData($limit = 0, $offset = 0) 
    {
        gc_enable();
        $this->em = $this->em = $this->container->get('doctrine')->getManager();
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $sql = "SELECT * FROM tm.players";
        
        if ($limit > 0 || $offset > 0)
            $sql .= " LIMIT ${offset}, ${limit}";

//        $this->em->getConnection()->executeQuery("SET FOREIGN_KEY_CHECKS = 0;");
        return $this->em->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function setContainer( ContainerInterface $container = null )
    {
        $this->container = $container;
    }

    public function getDependencies(): array 
    {
        return [TeamFixtures::class, CountryFixtures::class];
    }
}