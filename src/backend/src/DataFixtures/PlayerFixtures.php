<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Team;
use App\Type\PlayerRole\PlayerRoleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PlayerFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    /** @type ContainerInterface */
    private $container;
    
    public function load(ObjectManager $manager)
    {
        return;
        try {
            gc_enable();
    
            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->getConnection()->getConfiguration()->setSQLLogger(null);
        
            $stmt = $em->getConnection()->executeQuery("SELECT * FROM player.builder_player");
            $i = 0;
            while ($row = $stmt->fetch()) {
    
                if(empty($row["last_name"]) || empty($row["uid"]) || empty($row["alias"])) continue ;
                
    
                $player = (new Player())
                    ->setLastName($row["last_name"])
                    ->setTmId($row["uid"])
                    ->setAlias($row["alias"])
                ;
    
                if(!empty($row["first_name"]))  $player->setFirstName($row["first_name"]);
                if(!empty($row["native_name"])) $player->setNativeName($row["native_name"]);
                if(!empty($row["birthday"]))    $player->setBirthday(new \DateTime("@" . $row["birthday"]));
                if(!empty($row["birthplace"]))  $player->setBirthPlace($row["birthplace"]);
    //            if(!empty($row["country_id"])) $player->($row["country_id"]);
    //            if(!empty($row["nationality"])) $player->($row["nationality"]);
    //            if(!empty($row["nationality_f"])) $player->($row["nationality_f"]);
    //            if(!empty($row["nationality_m"])) $player->($row["nationality_m"]);
    //            if(!empty($row["status"])) $player->($row["status"]);
    //            if(!empty($row["link_to_tm"])) $player->($row["link_to_tm"]);
                
                if(!empty($row["foot"]) && in_array($row["foot"], ["left", "right", "both"])) {
                    $player->setFoot( str_replace(["left", "right", "both"], [0,1,2], $row["foot"]));
                }
                if(!empty($row["position"]))    $player->setRole(PlayerRoleFactory::createFromString(trim($row["position"])));
                if(!empty($row["height"]))      $player->setHeight($row["height"]);
                if(!empty($row["number"]))      $player->setNumber($row["number"]);
    
                if(!empty($row["preview"]))     $player->setAvatar(str_replace("https://tmssl.akamaized.net/", "", $row["preview"]));
                if(!empty($row["created"]))     $player->setCreated(new \DateTime("@" . $row["created"]));
                if(!empty($row["updated"]))     $player->setUpdated(new \DateTime("@" . $row["updated"]));
                if(!empty($row["contract_until"])) $player->setContractUntil(new \DateTime("@" . $row["contract_until"]));
                if(!empty($row["contract_ext"])) $player->setContractExt(new \DateTime("@" . $row["contract_ext"]));
                
                if(!empty($row["team_uid"]))    $player->setTeam($manager->getRepository(Team::class)->findOneBy(["tm_id" => $row["team_uid"]]) ?? null);
                if(!empty($row["in_team"]))     $player->setInTeam(new \DateTime("@" . $row["in_team"]));
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
        } catch (DBALException $e) {}
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