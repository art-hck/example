<?php
namespace App\Serializable;

use App\Entity\Country;
use App\Entity\Substitution;
use App\Entity\Team;
use App\Type\PlayerRole\PlayerRole;
use Doctrine\Common\Collections\Collection;

abstract class PlayerSerializable implements \JsonSerializable
{
    public function jsonSerialize() {
        return [
            "id" => $this->getId(),
            "tm_id" => $this->getTmId(),
            "first_name" => $this->getFirstName(),
            "last_name" => $this->getLastName(),
            "native_name" => $this->getNativeName(),
            "alias" => $this->getAlias(),
            "birthday" => $this->getBirthday() ? $this->getBirthday()->format(DATE_ISO8601) : null,
            "age" => $this->getBirthday() ? $this->getBirthday()->diff(new \DateTime())->y : null,
            "birthPlace" => $this->getBirthPlace(),
            "foot" => $this->getFoot(),
            "role" => $this->getRole(),
            "height" => $this->getHeight(),
            "number" => $this->getNumber(),
            "avatar" => $this->getAvatar(),
            "twitter" => $this->getTwitter(),
            "facebook" => $this->getFacebook(),
            "instagram" => $this->getInstagram(),
            "country" => $this->getCountry(),
            "birth_country" => $this->getBirthCountry(),
            "team" => $this->getTeam() ?? null,
            "cards" => $this->getCards(),
            "joined" => $this->getJoined(),
            "until" => $this->getUntil(),
            "transfers" => $this->getTransfers(),
            "goals_count" => count($this->getGoals()),
            "assists_count" => count($this->getAssists()),
            "play_time" => array_sum(array_map(
                function($substitution){
                /** @var $substitution Substitution */
                return $substitution->getPlayTime();
            }, $this->getSubstitutions()->toArray())),
        ];
    }

    abstract function getId(): ?int;
    abstract function getFirstName(): ?string;
    abstract function getLastName(): string;
    abstract function getNativeName(): ?string;
    abstract function getAlias(): string;
    abstract function getBirthday(): ?\DateTimeInterface;
    abstract function getBirthPlace(): ?string;
    abstract function getFoot(): ?int; 
    abstract function getRole(): ?PlayerRole;
    abstract function getHeight(): ?int;
    abstract function getNumber(): ?int;
    abstract function getAvatar(): ?string;
    abstract function getTwitter(): ?string;
    abstract function getFacebook(): ?string;
    abstract function getInstagram(): ?string;
    abstract function getTmId(): int;
    abstract function getCountry(): ?Country;
    abstract function getTeam(): ?Team;
    abstract function getBirthCountry(): ?Country;
    abstract function getJoined(): ?\DateTimeInterface;
    abstract function getUntil(): ?\DateTimeInterface;
    abstract function getCards(): Collection;
    abstract function getGoals(): Collection;
    abstract function getAssists(): Collection;
    abstract function getSubstitutions(): Collection;
    abstract function getTransfers(): Collection;
    
}