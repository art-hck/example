<?php

namespace App\Serializable;


use Doctrine\Common\Collections\Collection;

abstract class RefereeSerializable implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "games" => $this->getGames(),
        ];
    }

    abstract public function getId(): ?int;
    abstract public function getName(): ?string;
    abstract public function getGames(): Collection;
}