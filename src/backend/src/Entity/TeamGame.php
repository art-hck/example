<?php

namespace App\Entity;

use App\Serializable\TeamGameSerializable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamGameRepository")
 * @ORM\Table(indexes={
 *     @ORM\Index(name="IDX_GT", columns={"game_id", "team_id"}),
 *     @ORM\Index(name="IDX_TG", columns={"team_id", "game_id"})
 * })
 */
class TeamGame extends TeamGameSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="teamGames")
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="teamGames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
