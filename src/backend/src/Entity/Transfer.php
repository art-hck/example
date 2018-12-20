<?php

namespace App\Entity;

use App\Serializable\TransfersSerializable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransferRepository")
 */ 
class Transfer extends TransfersSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="transfers")
     */
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="transfers")
     */
    private $leftTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     */
    private $joinTeam;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fee;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mv;

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

    public function getLeftTeam(): ?Team
    {
        return $this->leftTeam;
    }

    public function setLeftTeam(?Team $leftTeam): self
    {
        $this->leftTeam = $leftTeam;

        return $this;
    }

    public function getJoinTeam(): ?Team
    {
        return $this->joinTeam;
    }

    public function setJoinTeam(?Team $joinTeam): self
    {
        $this->joinTeam = $joinTeam;

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

    public function getFee(): ?int
    {
        return $this->fee;
    }

    public function setFee(int $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    public function getMv(): ?int
    {
        return $this->mv;
    }

    public function setMv(?int $mv): self
    {
        $this->mv = $mv;

        return $this;
    }
}
