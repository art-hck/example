<?php

namespace App\Serializable;


use App\Entity\Country;
use Doctrine\Common\Collections\Collection;

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
        ];
    }
    
    abstract public function getId(): ?int;
    abstract public function getName(): ?string;
    abstract public function getAlias(): ?string ;
    abstract public function getPreview(): ?string ;
    abstract public function getCreated(): ?\DateTimeInterface;
    abstract public function getUpdated(): ?\DateTimeInterface;
    abstract public function getTmId(): ?int;
    abstract public function getCountry(): ?Country;
    abstract public function getPlayers(): Collection;
}