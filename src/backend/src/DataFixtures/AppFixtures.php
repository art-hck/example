<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Type\PlayerRole;
use App\Type\PlayerRole\PlayerRoleAttackingMidfield;
use App\Type\PlayerRole\PlayerRoleCentralMidfield;
use App\Type\PlayerRole\PlayerRoleCentreBack;
use App\Type\PlayerRole\PlayerRoleCentreForward;
use App\Type\PlayerRole\PlayerRoleDefender;
use App\Type\PlayerRole\PlayerRoleDefensiveMidfield;
use App\Type\PlayerRole\PlayerRoleForward;
use App\Type\PlayerRole\PlayerRoleGoalkeeper;
use App\Type\PlayerRole\PlayerRoleLeftBack;
use App\Type\PlayerRole\PlayerRoleLeftMidfield;
use App\Type\PlayerRole\PlayerRoleLeftWing;
use App\Type\PlayerRole\PlayerRoleMidfielder;
use App\Type\PlayerRole\PlayerRoleRightBack;
use App\Type\PlayerRole\PlayerRoleRightMidfield;
use App\Type\PlayerRole\PlayerRoleRightWing;
use App\Type\PlayerRole\PlayerRoleSecondaryStriker;
use App\Type\PlayerRole\PlayerRoleStriker;
use App\Type\PlayerRoleType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;

class AppFixtures extends Fixture implements ContainerAwareInterface
{
    private $container;
    
    public function load(ObjectManager $manager)
    {
        // 6475 9235 21862 52473 96287 96288 125274 125275 136123 139700 139701 143103 144491 144492 150682 158947 162750 162751 162753 162754 162755 162756 162757 162758 162759 162760 162763 162764 162765 162766 162767 162768 162769 162772 162773 162774 162775 162777 162778 162779 162780 162781 162782 162783 162784 162785 162786 162787 162788 162789 162790 162791 162793 162794 162796 162797 162798 162799 162800 162801 162802 162803 162809 162836 162837 162838 162839 162841 162842 162843 162844 162845 162846 162848 162863 162922 162926 162927 162928 162929 162930 162936 162938 162960 162996 164919 165734
        /**
         * @var EntityManager
        */
        $stmt = $this->container
            ->get('doctrine.orm.entity_manager') 
            ->getConnection()
            ->prepare("SELECT * FROM player.builder_player")
        ;
        
        $stmt->execute();
        $i = 0;
        while ($row = $stmt->fetch()) {

            if(empty($row["last_name"]) || empty($row["uid"])) {
                echo "ERROR WITH: " . $row["id"] . "\r\n";
                continue ;
            }
            
            $player = new Player();

            if(!empty($row["uid"]))         $player->setTmId($row["uid"]);
            if(!empty($row["first_name"]))  $player->setFirstName($row["first_name"]);
            if(!empty($row["last_name"]))   $player->setLastName($row["last_name"]);
            if(!empty($row["native_name"])) $player->setNativeName($row["native_name"]);
            if(!empty($row["alias"]))       $player->setAlias($row["alias"]);
            if(!empty($row["birthday"]))    $player->setBirthday(new \DateTime("@" . $row["birthday"]));
            if(!empty($row["birthplace"]))  $player->setBirthPlace($row["birthplace"]);
//            if(!empty($row["country_id"])) $player->($row["country_id"]);
//            if(!empty($row["nationality"])) $player->($row["nationality"]);
//            if(!empty($row["nationality_f"])) $player->($row["nationality_f"]);
//            if(!empty($row["nationality_m"])) $player->($row["nationality_m"]);
            
            if(!empty($row["foot"]) && in_array($row["foot"], ["left", "right", "both"])) {
                $player->setFoot( str_replace(["left", "right", "both"], [0,1,2], $row["foot"]));
            }
            if(!empty($row["position"]))    $player->setRole($this->createPlayerRoleFromString(trim($row["position"])));
            if(!empty($row["height"]))      $player->setHeight($row["height"]);
            if(!empty($row["number"]))      $player->setNumber($row["number"]);

            if(!empty($row["preview"]))     $player->setAvatar(str_replace("https://tmssl.akamaized.net/", "", $row["preview"]));
            if(!empty($row["created"]))     $player->setCreated(new \DateTime("@" . $row["created"]));
            if(!empty($row["updated"]))     $player->setUpdated(new \DateTime("@" . $row["updated"]));
//            if(!empty($row["status"])) $player->($row["status"]);
//            if(!empty($row["link_to_tm"])) $player->($row["link_to_tm"]);
            if(!empty($row["contract_until"])) $player->setContractUntil(new \DateTime("@" . $row["contract_until"]));
            if(!empty($row["contract_ext"])) $player->setContractExt(new \DateTime("@" . $row["contract_ext"]));
            if(!empty($row["team_uid"]))    $player->setTeamId($row["team_uid"]);
            if(!empty($row["in_team"]))     $player->setInTeam(new \DateTime("@" . $row["in_team"]));
            if(!empty($row["twitter"]))     $player->setTwitter($row["twitter"]);
            if(!empty($row["facebook"]))    $player->setFacebook($row["facebook"]);
            if(!empty($row["instagram"]))   $player->setInstagram($row["instagram"]);
            if(!empty($row["agents"]))      $player->setAgents(trim($row["agents"]));

            $manager->persist($player);
            if($i++ > 1000) {
                $i = 0;
                $manager->flush();
                $manager->clear();
            }
        }

        $manager->flush();
    }

    public function setContainer( ContainerInterface $container = null )
    {
        $this->container = $container;
    }
    
    private function createPlayerRoleFromString(string $role): ?PlayerRole 
    {
        switch ($role) {
            case "Attacking Midfield": return new PlayerRoleAttackingMidfield();
            case "Central Midfield": return new PlayerRoleCentralMidfield();
            case "Centre-Back": return new PlayerRoleCentreBack();
            case "Centre-Forward": return new PlayerRoleCentreForward();
            case "Defence": return new PlayerRoleDefender();
            case "Defensive Midfield": return new PlayerRoleDefensiveMidfield();
            case "Goalkeeper": return new PlayerRoleGoalkeeper();
            case "Keeper": return new PlayerRoleGoalkeeper();
            case "Left Midfield": return new PlayerRoleLeftMidfield();
            case "Left Wing": return new PlayerRoleLeftWing();
            case "Left-Back": return new PlayerRoleLeftBack();
            case "Midfield": return new PlayerRoleMidfielder();
            case "Right Midfield": return new PlayerRoleRightMidfield();
            case "Right Wing": return new PlayerRoleRightWing();
            case "Right-Back": return new PlayerRoleRightBack();
            case "Secondary Striker": return new PlayerRoleSecondaryStriker();
            case "Striker": return new PlayerRoleStriker();
            case "Defender": return new PlayerRoleDefender();
            case "Forward": return new PlayerRoleForward();
            case "Left Winger": return new PlayerRoleLeftWing();
            case "Midfielder": return new PlayerRoleMidfielder();
            case "Right Winger": return new PlayerRoleRightWing();
            case "Second Striker": return new PlayerRoleSecondaryStriker();
            case "Sweeper": return new PlayerRoleStriker();            
            default: return null; 
        }
    }
}
