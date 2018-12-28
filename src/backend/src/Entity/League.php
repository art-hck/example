<?php

namespace App\Entity;

use App\Serializable\LeagueSerializable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LeagueRepository")
 * @ORM\Table(indexes={
 *     @ORM\Index(name="IDX_LEAGUE_NAME", columns={"name"})
 * })
 */
class League extends LeagueSerializable 
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
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="league", orphanRemoval=true)
     */
    private $games;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isInternational;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="leagues")
     */
    private $country;

    /**
     * @ORM\Column(type="integer")
     */
    private $tmId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $зк�preview;

    public function __construct()
    {
        $this->games = new ArrayCollection();
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

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setLeague($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getLeague() === $this) {
                $game->setLeague(null);
            }
        }

        return $this;
    }

    public function getIsInternational(): ?bool
    {
        return $this->isInternational;
    }

    public function setIsInternational(bool $isInternational): self
    {
        $this->isInternational = $isInternational;

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

    public function getTmId(): ?int
    {
        return $this->tmId;
    }

    public function setTmId(int $tmId): self
    {
        $this->tmId = $tmId;

        return $this;
    }

    public function getзк�preview(): ?string
    {
        return $this->зк�preview;
    }

    public function setзк�preview(?string $зк�preview): self
    {
        $this->зк�preview = $зк�preview;

        return $this;
    }
}
