<?php

namespace App\Tests;

use App\Entity\Player;
use App\Entity\Team;
use App\Type\PlayerRole\PlayerRoleFactory;
use App\Type\PlayerRole\Types\PlayerRoleAttackingMidfield;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetPlayersTest extends WebTestCase
{
    public function testGetPlayer()
    {
        $client = static::createClient();
        $client->request('GET', '/player/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $p1 = (new Player())
            ->setTmId(0)
            ->setLastName("")
            ->setAlias("")
            ->setCreated(new \DateTime())
            ->setUpdated(new \DateTime())
            ->setRole(new PlayerRoleAttackingMidfield())
        ;
        
        $this->assertEquals(
            array_keys(
                get_object_vars(
                    json_decode(
                        json_encode($p1)
                    )
                )
            ), 
            array_keys(
                get_object_vars(
                    json_decode(
                        $client->getResponse()->getContent()
                    )
                )
            )
        );
    }
}