<?php

namespace App\Serializable;

use App\Entity\Assist;
use App\Entity\Game;
use App\Entity\Player;

abstract class CardSerializable implements \JsonSerializable
{
    public function jsonSerialize($depth = 0)
    {
        return [
            "id" => $this->getId(),
            "player" => $depth > 2 ? null : $this->getPlayer(),
            "time" => $this->getTime(),
            "reason" => $this->getReason(),
            "type" => $this->getType(),
        ];
    }

    abstract public function getId(): ?int;
    abstract public function getPlayer(): ?Player;
    abstract public function getTime(): ?int;
    abstract public function getReason(): ?string;
    abstract public function getType(): ?int;
    abstract public function getGame(): ?Game;
}