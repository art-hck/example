<?php

namespace App\Type\SeekCriteria\Types;

use App\Type\SeekCriteria\SeekCriteria;
use App\Type\SeekCriteria\SeekCriteriaRange;

class SeekCriteriaPlayerFilter extends SeekCriteria
{
    public const orderByFields = [
        "id", "birthday", "birthPlace",
        "foot", "role", "height",
        "number", "country", "team",
        "goals", "cards", "playTime"
    ];

    private $datePeriod;
    private $leagueId;
    private $teamId;
    private $goalsRange;
    private $playTimeRange;
    private $cardsRange;
    private $cardsType;
    
    public function getDatePeriod(): ?SeekCriteriaRange
    {
        return $this->datePeriod;
    }

    public function setDatePeriod(?\DateTime $dateFrom, ?\DateTime $dateTo): self
    {
        if($dateFrom || $dateTo) {
            $this->datePeriod = new SeekCriteriaRange($dateFrom, $dateTo);
        }

        return $this;
    }

    public function getLeagueId()
    {
        return $this->leagueId;
    }

    public function setLeagueId(?int $leagueId): self
    {
        $this->leagueId = $leagueId;

        return $this;
    }

    public function getTeamId(): ?int
    {
        return $this->teamId;
    }

    public function setTeamId(?int $teamId): self
    {
        $this->teamId = $teamId;

        return $this;
    }

    public function getCardsType(): ?int
    {
        return $this->cardsType;
    }

    public function setCardsType(?int $cardsType): self
    {
        $this->cardsType = $cardsType;

        return $this;
    }

    public function getGoalsRange(): ?SeekCriteriaRange
    {
        return $this->goalsRange;
    }

    public function setGoalsRange(?int $min, ?int $max): self
    {
        if($min || $max) {
            $this->goalsRange = new SeekCriteriaRange($min, $max);
        }

        return $this;
    }

    public function getPlayTimeRange(): ?SeekCriteriaRange
    {
        return $this->playTimeRange;
    }

    public function setPlayTimeRange(?int $min, ?int $max): self
    {
        if($min || $max) {
            $this->playTimeRange = new SeekCriteriaRange($min, $max);
        }

        return $this;
    }

    public function getCardsRange(): ?SeekCriteriaRange
    {
        return $this->cardsRange;
    }

    public function setCardsRange(?int $min, ?int $max): self
    {
        if($min || $max) {
            $this->cardsRange = new SeekCriteriaRange($min, $max);
        }

        return $this;
    }

    public static function getOrderByFields(): array
    {
        return self::orderByFields;
    }
}
