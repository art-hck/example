<?php

namespace App\Serializable;

use App\Entity\League;
use App\Entity\Referee;
use App\Entity\Stadium;
use App\Entity\Substitution;
use App\Entity\TeamGame;
use Doctrine\Common\Collections\Collection;

abstract class GameSerializable implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "league" => $this->getLeague(),
            "day" => $this->getDay(),
            "date" => $this->getDate()->format(DATE_ISO8601),
            "duration" => $this->getDuration(),
            "score" => $this->getScore(),
            "stadium" => $this->getStadium(),
            "referee" => $this->getReferee(),
            "goals" => $this->getGoals(),
            "cards" => $this->getCards(),
            "status" => $this->getStatus(),
            "updated" => $this->getUpdated(),
            "attendance" => $this->getAttendance(),
            "tm_id" => $this->getTmId(),
            "substitutions" => $this->getSubstitutions(),
            "teams" => $this->getTeamGames()->map(function ($teamGame) {
                /** @var TeamGame $teamGame */
                return $teamGame->getTeam();
            })->toArray(),
        ];
    }

    abstract public function getId(): ?int;
    abstract public function getLeague(): ?League;
    abstract public function getDay(): ?int;
    abstract public function getDate(): ?\DateTimeInterface;
    abstract public function getDuration(): ?int;
    abstract public function getScore(): ?string;
    abstract public function getStadium(): ?Stadium;
    abstract public function getReferee(): ?Referee;
    abstract public function getGoals(): Collection;
    abstract public function getCards(): Collection;
    abstract public function getStatus(): ?int;
    abstract public function getUpdated(): ?\DateTimeInterface;
    abstract public function getAttendance(): ?float;
    abstract public function getTmId(): ?int;
    abstract public function getSubstitutions(): Collection;
    abstract public function getTeamGames(): ?Collection;
}