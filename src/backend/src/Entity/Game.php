<?php

namespace App\Entity;

use App\Serializable\GameSerializable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @ORM\Table(indexes={
 *     @ORM\Index(name="IDX_DATE", columns={"date"})
 * })
 */
class Game extends GameSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\League", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $league;

    /**
     * @ORM\Column(type="integer")
     */
    private $day;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $score;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Stadium", inversedBy="games")
     * @ORM\JoinColumn(nullable=true)
     * @TODO: nullable must be false!!!!
     */
    private $stadium;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Referee", inversedBy="games")
     * @ORM\JoinColumn(nullable=true)
     */
    private $referee;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Goal", mappedBy="game", orphanRemoval=true)
     */
    private $goals;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="game")
     */
    private $cards;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $attendance;

    /**
     * @ORM\Column(type="integer")
     */
    private $tmId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Substitution", mappedBy="game")
     */
    private $substitutions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeamGame", mappedBy="game")
     */
    private $teamGames;

    public function __construct()
    {
        $this->goals = new ArrayCollection();
        $this->cards = new ArrayCollection();
        $this->substitutions = new ArrayCollection();
        $this->teamGames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): self
    {
        $this->league = $league;

        return $this;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(?string $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getStadium(): ?Stadium
    {
        return $this->stadium;
    }

    public function setStadium(?Stadium $stadium): self
    {
        $this->stadium = $stadium;

        return $this;
    }

    public function getReferee(): ?Referee
    {
        return $this->referee;
    }

    public function setReferee(?Referee $referee): self
    {
        $this->referee = $referee;

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
            $goal->setGame($this);
        }

        return $this;
    }

    public function removeGoal(Goal $goal): self
    {
        if ($this->goals->contains($goal)) {
            $this->goals->removeElement($goal);
            // set the owning side to null (unless already changed)
            if ($goal->getGame() === $this) {
                $goal->setGame(null);
            }
        }

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
            $card->setGame($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getGame() === $this) {
                $card->setGame(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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

    public function getAttendance(): ?float
    {
        return $this->attendance;
    }

    public function setAttendance(?float $attendance): self
    {
        $this->attendance = $attendance;

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
            $substitution->setGame($this);
        }

        return $this;
    }

    public function removeSubstitution(Substitution $substitution): self
    {
        if ($this->substitutions->contains($substitution)) {
            $this->substitutions->removeElement($substitution);
            // set the owning side to null (unless already changed)
            if ($substitution->getGame() === $this) {
                $substitution->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TeamGame[]
     */
    public function getTeamGames(): Collection
    {
        return $this->teamGames;
    }

    public function addTeamGame(TeamGame $teamGame): self
    {
        if (!$this->teamGames->contains($teamGame)) {
            $this->teamGames[] = $teamGame;
            $teamGame->setGame($this);
        }

        return $this;
    }

    public function removeTeamGame(TeamGame $teamGame): self
    {
        if ($this->teamGames->contains($teamGame)) {
            $this->teamGames->removeElement($teamGame);
            // set the owning side to null (unless already changed)
            if ($teamGame->getGame() === $this) {
                $teamGame->setGame(null);
            }
        }

        return $this;
    }
}
