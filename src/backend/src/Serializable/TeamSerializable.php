<?php

namespace App\Serializable;


abstract class TeamSerializable implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "alias" => $this->getAlias(),
            "preview" => $this->getPreview(),
            "created" => $this->getCreated(),
            "updated" => $this->getUpdated(),
            "tm_id" => $this->getTmId(),
            "country" => $this->getCountry(),
            "players" => $this->getPlayers(),
            "homeGames" => $this->getHomeGames(),
            "guestGames" => $this->getGuestGames(),
        ];
    }
    
    abstract public function getId();
    abstract public function getName();
    abstract public function getAlias();
    abstract public function getPreview();
    abstract public function getCreated();
    abstract public function getUpdated();
    abstract public function getTmId();
    abstract public function getCountry();
    abstract public function getPlayers();
    abstract public function getHomeGames();
    abstract public function getGuestGames();
}