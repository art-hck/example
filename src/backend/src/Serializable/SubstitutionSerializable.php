<?php

namespace App\Serializable;

use App\Entity\Assist;
use App\Entity\Game;
use App\Entity\Player;

abstract class SubstitutionSerializable implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'player' => $this->getPlayer(),
            'game' => $this->getGame(),
            'join_time' => $this->getJoinTime(),
            'play_time' => $this->getPlayTime(),
        ];
    }
    abstract public function getId(): ?int;
    abstract public function getPlayer(): ?Player;
    abstract public function getGame(): ?Game;
    abstract public function getJoinTime(): ?int;
    abstract public function getPlayTime(): ?int;
}