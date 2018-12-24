<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Player;
use App\Entity\Team;
use App\Type\PlayerRole\PlayerRoleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
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
            gc_enable();
            $this->em = $this->em = $this->container->get('doctrine')->getManager();
            $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        
            $stmt = $this->em->getConnection()->executeQuery("SELECT * FROM tm.builder_player");
            $i = 0;
            while ($row = $stmt->fetch()) {
    
                if(empty($row["lastName"]) || empty($row["playerUID"]) || empty($row["alias"])) continue ;
                
    
                $player = (new Player())
                    ->setLastName($row["lastName"])
                    ->setTmId($row["playerUID"])
                    ->setAlias($row["alias"])
                ;
    
                if(!empty($row["firstName"]))  $player->setFirstName($row["firstName"]);
                if(!empty($row["nativeName"])) $player->setNativeName($row["nativeName"]);
                if(!empty($row["birthdaystamp"]))    $player->setBirthday(new \DateTime("@" . $row["birthdaystamp"]));
                if(!empty($row["birthplace"]))  $player->setBirthPlace($row["birthplace"]);
                if(!empty($row["birthcountryUID"]))  $player->setBirthCountry($this->em->getReference(Country::class, $row["birthcountryUID"]));
                if(!empty($row["nationalityUID"]))  $player->setCountry($this->em->getReference(Country::class, $row["nationalityUID"]));
                
                if(!empty($row["foot"])) $player->setFoot($row["foot"]);
                if(!empty($row["position"]))    $player->setRole(PlayerRoleFactory::createFromString(trim($row["position"])));
                if(!empty($row["height"]))      $player->setHeight($row["height"]);
                if(!empty($row["playerNumber"]))      $player->setNumber($row["playerNumber"]);
    
                if(!empty($row["playerImg"]))     $player->setAvatar(str_replace("https://tmssl.akamaized.net/", "", $row["playerImg"]));
                if(!empty($row["contract_until"])) $player->setContractUntil(new \DateTime("@" . $row["contract_until"]));
                if(!empty($row["contract_ext"])) $player->setContractExt(new \DateTime("@" . $row["contract_ext"]));
                
                
                if(!empty($row["clubUID"])) {
                    /** @var Team $team */
                    $team = $this->em->getReference(Team::class, $row["clubUID"]);
                    if($team) $player->setTeam($team);
                }
                
                if(!empty($row["joined"]))      $player->setInTeam(new \DateTime($row["joined"]));
                if(!empty($row["twitter"]))     $player->setTwitter($row["twitter"]);
                if(!empty($row["facebook"]))    $player->setFacebook($row["facebook"]);
                if(!empty($row["instagram"]))   $player->setInstagram($row["instagram"]);
                if(!empty($row["agents"]))      $player->setAgents(trim($row["agents"]));
    
                $manager->persist($player);
    
                if($i++ > 10000) {
                    $i = 0;
                    $manager->flush();
                    $manager->clear();
                    gc_collect_cycles();
                }
            }

            $manager->flush();
            $manager->clear();
            gc_collect_cycles();
        } catch (DBALException | ORMException $e) {
            dump($e);
        }
    }

    public function setContainer( ContainerInterface $container = null )
    {
        $this->container = $container;
    }

    public function getDependencies(): array 
    {
        return [TeamFixtures::class];
    }
}