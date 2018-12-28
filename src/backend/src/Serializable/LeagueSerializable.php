<?php

namespace App\Serializable;


use App\Entity\Country;

abstract class LeagueSerializable implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "country" => $this->getCountry(),
        ];
    }

    abstract public function getId(): ?int;
    abstract public function getName(): ?string;
    abstract public function getCountry(): ?Country;
}