<?php

namespace App\Type\SeekCriteria\Types;

use App\Type\SeekCriteria\SeekCriteria;
use App\Type\SeekCriteria\SeekCriteriaRange;

class SeekCriteriaGameFilter extends SeekCriteria
{
    public const orderByFields = ["id", "date"];

    private $datePeriod;
    private $teamId;
    private $leagueId;
    private $duration;

    public function getDatePeriod(): ?SeekCriteriaRange
    {
        return $this->datePeriod;
    }

    public function setDatePeriod(?\DateTime $dateFrom, ?\DateTime $dateTo): self
    {
        if ($dateFrom || $dateTo) {
            $this->datePeriod = new SeekCriteriaRange($dateFrom, $dateTo);
        }

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

    public function getLeagueId(): ?int
    {
        return $this->leagueId;
    }

    public function setLeagueId(?int $leagueId): self
    {
        $this->leagueId = $leagueId;

        return $this;
    }

    public function getDuration(): ?SeekCriteriaRange
    {
        return $this->duration;
    }

    public function setDuration(?SeekCriteriaRange $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    static function getOrderByFields(): array
    {
        return self::orderByFields;
    }
}
