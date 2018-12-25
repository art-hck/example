<?php

namespace App\Serializable;

use App\Entity\Assist;
use App\Entity\Game;
use App\Entity\Player;
use Doctrine\Common\Collections\Collection;

abstract class CountrySerializable implements \JsonSerializable
{
    public function jsonSerialize($depth = 0)
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "alias" => $this->getAlias(),
            "players" => $this->getPlayers()
        ];
    }

    abstract function getId(): ?int;
    abstract function getName(): ?string;
    abstract function getAlias(): ?string;
    abstract function getPlayers(): Collection;
}