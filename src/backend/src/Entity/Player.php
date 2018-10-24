<?php

namespace App\Entity;

use App\Serializable\PlayerSerializable;
use App\Type\PlayerRole\PlayerRole;
use App\Type\PlayerRole\PlayerRoleFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
//use App\DBAL\Types\BasketballPositionType;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player extends PlayerSerializable
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="player")
     */
    private $cards;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Goal", mappedBy="player")
     */
    private $goals;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Assist", mappedBy="player", orphanRemoval=true)
     */
    private $assists;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Substitution", mappedBy="player")
     */
    private $substitutions;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->goals = new ArrayCollection();
        $this->assists = new ArrayCollection();
        $this->substitutions = new ArrayCollection();
    }

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

    public function getBirthday(): ?\DateTimeInterface
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
        return PlayerRoleFactory::createFromId($this->role);
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

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setPlayer($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getPlayer() === $this) {
                $card->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Goal[]
     */
    public function getGoals(): Collection
    {
        return $this->goals;
    }

    public function addGoal(Goal $goal): self
    {
        if (!$this->goals->contains($goal)) {
            $this->goals[] = $goal;
            $goal->setPlayer($this);
        }

        return $this;
    }

    public function removeGoal(Goal $goal): self
    {
        if ($this->goals->contains($goal)) {
            $this->goals->removeElement($goal);
            // set the owning side to null (unless already changed)
            if ($goal->getPlayer() === $this) {
                $goal->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Assist[]
     */
    public function getAssists(): Collection
    {
        return $this->assists;
    }

    public function addAssist(Assist $assist): self
    {
        if (!$this->assists->contains($assist)) {
            $this->assists[] = $assist;
            $assist->setPlayer($this);
        }

        return $this;
    }

    public function removeAssist(Assist $assist): self
    {
        if ($this->assists->contains($assist)) {
            $this->assists->removeElement($assist);
            // set the owning side to null (unless already changed)
            if ($assist->getPlayer() === $this) {
                $assist->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Substitution[]
     */
    public function getSubstitutions(): Collection
    {
        return $this->substitutions;
    }

    public function addSubstitution(Substitution $substitution): self
    {
        if (!$this->substitutions->contains($substitution)) {
            $this->substitutions[] = $substitution;
            $substitution->setPlayer($this);
        }

        return $this;
    }

    public function removeSubstitution(Substitution $substitution): self
    {
        if ($this->substitutions->contains($substitution)) {
            $this->substitutions->removeElement($substitution);
            // set the owning side to null (unless already changed)
            if ($substitution->getPlayer() === $this) {
                $substitution->setPlayer(null);
            }
        }

        return $this;
    }
}
