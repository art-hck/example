<?php

namespace App\Serializable;

use App\Entity\Country;
use App\Entity\League;
use App\Entity\Referee;
use App\Entity\Stadium;
use App\Entity\Team;
use App\Type\PlayerRole\PlayerRole;
use Doctrine\Common\Collections\Collection;

abstract class PlayerRoleSerializable implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }

    abstract function getId(): int;
    abstract function getName(): string;
}