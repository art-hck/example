<?php

namespace App\Entity;

use App\Serializable\SubstitutionSerializable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubstitutionRepository")
 * @ORM\Table(indexes={
 *     @ORM\Index(name="IDX_GTP", columns={"game_id", "play_time", "player_id"})
 * })
 */
class Substitution extends SubstitutionSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="substitutions")
     * @ORM\JoinColumn(nullable=true)
     * @TODO: nullable must be false!!!!
     */
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="substitutions")
     */
    private $game;

    /**
     * @ORM\Column(type="integer")
     */
    private $joinTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $playTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

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

    public function getJoinTime(): ?int
    {
        return $this->joinTime;
    }

    public function setJoinTime(int $joinTime): self
    {
        $this->joinTime = $joinTime;

        return $this;
    }

    public function getPlayTime(): ?int
    {
        return $this->playTime;
    }

    public function setPlayTime(int $playTime): self
    {
        $this->playTime = $playTime;

        return $this;
    }
}
