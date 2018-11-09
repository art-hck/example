<?php

namespace App\Serializable;

use App\Entity\Assist;
use App\Entity\Game;
use App\Entity\Player;

abstract class GoalSerializable implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "player" => $this->getPlayer(),
            "time" => $this->getTime(),
            "score" => $this->getScore(),
            "description" => $this->getDescription(),
            "assist" => $this->getAssist(),
            "game" => $this->getGame(),
        ];
    }

    abstract public function getId(): ?int;
    abstract public function getPlayer(): ?Player;
    abstract public function getTime(): ?int;
    abstract public function getScore(): ?string;
    abstract public function getDescription(): ?string;
    abstract public function getAssist(): ?Assist;
    abstract public function getGame(): ?Game;
}