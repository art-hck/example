<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alias;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preview;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="integer")
     */
    private $tm_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="team")
     */
    private $players;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="homeTeam", orphanRemoval=true)
     */
    private $homeGames;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="guestTeam")
     */
    private $guestGames;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->homeGames = new ArrayCollection();
        $this->guestGames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): self
    {
        $this->preview = $preview;

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

    public function getTmId(): ?int
    {
        return $this->tm_id;
    }

    public function setTmId(int $tm_id): self
    {
        $this->tm_id = $tm_id;

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

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

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

    /**
     * @return Collection|Game[]
     */
    public function getHomeGames(): Collection
    {
        return $this->homeGames;
    }

    public function addHomeGame(Game $homeGame): self
    {
        if (!$this->homeGames->contains($homeGame)) {
            $this->homeGames[] = $homeGame;
            $homeGame->setHomeTeam($this);
        }

        return $this;
    }

    public function removeHomeGame(Game $homeGame): self
    {
        if ($this->homeGames->contains($homeGame)) {
            $this->homeGames->removeElement($homeGame);
            // set the owning side to null (unless already changed)
            if ($homeGame->getHomeTeam() === $this) {
                $homeGame->setHomeTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGuestGames(): Collection
    {
        return $this->guestGames;
    }

    public function addGuestGame(Game $guestGame): self
    {
        if (!$this->guestGames->contains($guestGame)) {
            $this->guestGames[] = $guestGame;
            $guestGame->setGuestTeam($this);
        }

        return $this;
    }

    public function removeGuestGame(Game $guestGame): self
    {
        if ($this->guestGames->contains($guestGame)) {
            $this->guestGames->removeElement($guestGame);
            // set the owning side to null (unless already changed)
            if ($guestGame->getGuestTeam() === $this) {
                $guestGame->setGuestTeam(null);
            }
        }

        return $this;
    }
}
