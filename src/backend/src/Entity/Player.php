<?php

namespace App\Entity;

use App\Type\PlayerRole\PlayerRole;
use App\Type\PlayerRole\PlayerRoleFactory;
use Doctrine\ORM\Mapping as ORM;
//use App\DBAL\Types\BasketballPositionType;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $tmId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nativeName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alias;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $birthPlace;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $foot;

    /**
     * @ORM\Column(type="string", type="PlayerRoleType", nullable=true)
     * @DoctrineAssert\Enum(entity="App\DBAL\Types\PlayerRoleType")
     */
    private $role;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $contractUntil;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $contractExt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $agents;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $inTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="players")
     */
    private $team;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getNativeName(): ?string
    {
        return $this->nativeName;
    }

    public function setNativeName(?string $nativeName): self
    {
        $this->nativeName = $nativeName;

        return $this;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getBirthday(): \DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getBirthPlace(): ?string
    {
        return $this->birthPlace;
    }

    public function setBirthPlace(?string $birthPlace): self
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    public function getFoot(): ?int 
    {
        return $this->foot;
    }

    public function setFoot(?int $foot): self
    {
        $this->foot = $foot;

        return $this;
    }

    public function getRole(): ?PlayerRole
    {
        return PlayerRoleFactory::createFromString($this->role);
    }

    public function setRole(?PlayerRole $role): self
    {
        if($role instanceof PlayerRole) {
            $this->role = $role->getId();
        }

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getContractUntil(): ?\DateTimeInterface
    {
        return $this->contractUntil;
    }

    public function setContractUntil(?\DateTimeInterface $contractUntil): self
    {
        $this->contractUntil = $contractUntil;

        return $this;
    }

    public function getContractExt(): ?\DateTimeInterface
    {
        return $this->contractExt;
    }

    public function setContractExt(?\DateTimeInterface $contractExt): self
    {
        $this->contractExt = $contractExt;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getAgents(): ?string
    {
        return $this->agents;
    }

    public function setAgents(?string $agents): self
    {
        $this->agents = $agents;

        return $this;
    }

    public function getTmId(): int
    {
        return $this->tmId;
    }

    public function setTmId(int $tmId): self
    {
        $this->tmId = $tmId;

        return $this;
    }

    public function getInTeam(): ?\DateTimeInterface
    {
        return $this->inTeam;
    }

    public function setInTeam(?\DateTimeInterface $inTeam): self
    {
        $this->inTeam = $inTeam;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }


    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "tm_id" => $this->getTmId(),
            "first_name" => $this->getFirstName(),
            "last_name" => $this->getLastName(),
            "native_name" => $this->getNativeName(),
            "alias" => $this->getAlias(),
            "birthday" => $this->getBirthday(),
            "birthPlace" => $this->getBirthPlace(),
            "foot" => $this->getFoot(),
            "role" => $this->getRole(),
            "height" => $this->getHeight(),
            "number" => $this->getNumber(),
            "avatar" => $this->getAvatar(),
            "created" => $this->getCreated(),
            "updated" => $this->getUpdated(),
            "contract_until" => $this->getContractUntil(),
            "contract_ext" => $this->getContractExt(),
            "twitter" => $this->getTwitter(),
            "facebook" => $this->getFacebook(),
            "instagram" => $this->getInstagram(),
            "agents" => $this->getAgents(),
            "in_team" => $this->getInTeam(),
            "country" => $this->getCountry(),
            "team" => $this->getTeam(),
        ];
    }
}
