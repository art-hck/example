<?php

namespace App\Serializable;


abstract class LeagueSerializable implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "season" => $this->getSeason()
        ];
    }

    abstract public function getId(): ?int;
    abstract public function getName(): ?string;
    abstract public function getSeason(): ?int;
}