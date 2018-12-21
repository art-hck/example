<?php

namespace App\Serializable;


use App\Entity\Player;
use App\Entity\Team;

abstract class TransfersSerializable implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "player" => $this->getPlayer(),
            "left_team" => $this->getLeftTeam(),
            "join_team" => $this->getJoinTeam(),
            "date" => $this->getDate()->format(DATE_ISO8601),
            "fee" => $this->getFee(),
            "mv" => $this->getMv(),
        ];
    }
    
    abstract public function getId(): ?int;
    abstract public function getPlayer(): ?Player;
    abstract public function getLeftTeam(): ?Team;
    abstract public function getJoinTeam(): ?Team;
    abstract public function getDate(): ?\DateTimeInterface;
    abstract public function getFee(): ?int;
    abstract public function getMv(): ?int;
}