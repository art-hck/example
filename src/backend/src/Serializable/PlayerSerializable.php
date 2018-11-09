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
            "created" => $this->getCreated()->format(DATE_ISO8601),
            "updated" => $this->getUpdated()->format(DATE_ISO8601),
            "contract_until" => $this->getContractUntil() ? $this->getContractUntil()->format(DATE_ISO8601) : null,
            "contract_ext" => $this->getContractExt() ? $this->getContractExt()->format(DATE_ISO8601) : null,
            "twitter" => $this->getTwitter(),
            "facebook" => $this->getFacebook(),
            "instagram" => $this->getInstagram(),
            "agents" => $this->getAgents(),
            "in_team" => $this->getInTeam(),
            "country" => $this->getCountry(),
            "team" => $this->getTeam(),
            "cards" => $this->getCards(),
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
    abstract function getTmId(): int;
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
    abstract function getCreated(): ?\DateTimeInterface;
    abstract function getUpdated(): ?\DateTimeInterface;
    abstract function getContractUntil(): ?\DateTimeInterface;
    abstract function getContractExt(): ?\DateTimeInterface;
    abstract function getTwitter(): ?string;
    abstract function getFacebook(): ?string;
    abstract function getInstagram(): ?string;
    abstract function getAgents(): ?string;
    abstract function getInTeam(): ?\DateTimeInterface;
    abstract function getCountry(): ?Country;
    abstract function getTeam(): ?Team;
    abstract function getCards(): Collection;
    abstract function getGoals(): Collection;
    abstract function getAssists(): Collection;
    abstract function getSubstitutions(): Collection;
}