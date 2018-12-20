<?php

namespace App\Serializable;

use App\Entity\Game;
use App\Entity\Team;

abstract class TeamGameSerializable implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "team" => $this->getTeam(),
            "game" => $this->getGame(),
            "type" => $this->getType(),
        ];
    }

    abstract public function getId(): ?int;
    abstract public function getTeam(): ?Team;
    abstract public function getGame(): ?Game;
    abstract public function getType(): ?int;
}