<?php

namespace App\Type\SeekCriteria\Types;

use App\Type\PlayerRole\PlayerRole;
use App\Type\SeekCriteria\SeekCriteria;
use App\Type\SeekCriteria\SeekCriteriaException;
use App\Type\SeekCriteria\SeekCriteriaRange;

class SeekCriteriaPlayerFilter extends SeekCriteria
{
    public const orderByFields = [
        "id", "birthday", "birthPlace",
        "foot", "role", "height",
        "number", "country", "team",
        "goals", "cards", "playTime", "name"
    ];

    private $datePeriod;
    private $leagueId;
    private $leagueName;
    private $leagueSeason;
    private $teamId;
    private $teamName;
    private $role;
    private $goalsRange;
    private $ageRange;
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
        try {
            $this->goalsRange = new SeekCriteriaRange($min, $max);
        } catch (SeekCriteriaException $e) {}

        return $this;
    }

    public function getPlayTimeRange(): ?SeekCriteriaRange
    {
        return $this->playTimeRange;
    }

    public function setPlayTimeRange(?int $min, ?int $max): self
    {
        try {
            $this->playTimeRange = new SeekCriteriaRange($min, $max);
        } catch (SeekCriteriaException $e) {}

        return $this;
    }

    public function getCardsRange(): ?SeekCriteriaRange
    {
        return $this->cardsRange;
    }

    public function setCardsRange(?int $min, ?int $max): self
    {
        try {
            $this->cardsRange = new SeekCriteriaRange($min, $max);
        } catch (SeekCriteriaException $e) {}

        return $this;
    }

    public static function getOrderByFields(): array
    {
        return self::orderByFields;
    }

    public function getLeagueSeason(): ?int
    {
        return $this->leagueSeason;
    }
    
    public function setLeagueSeason(int $leagueSeason): self 
    {
        $this->leagueSeason = $leagueSeason;

        return $this;
    }

    public function getLeagueName(): ?string 
    {
        return $this->leagueName;
    }

    public function setLeagueName($leagueName): self
    {
        $this->leagueName = $leagueName;
        
        return $this;
    }

    public function getAgeRange(): ?SeekCriteriaRange
    {
        return $this->ageRange;
    }

    public function setAgeRange(?int $min, ?int $max): self
    {
        try {
            $this->ageRange = new SeekCriteriaRange($min, $max);
        } catch (SeekCriteriaException $e) {}
        
        return $this;
    }

    public function getRole(): ?PlayerRole
    {
        return $this->role;
    }

    public function setRole(?PlayerRole $role): self
    {
        $this->role = $role;
        
        return $this;
    }

    public function getTeamName(): ?string 
    {
        return $this->teamName;
    }

    public function setTeamName(?string $teamName): self 
    {
        $this->teamName = $teamName;
        
        return $this;
    }
}
