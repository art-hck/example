<?php

namespace App\Type\SeekCriteria;

class SeekCriteria
{
    private $datePeriod;
    private $leagueId;
    private $minGoals;
    private $maxGoals;
    private $teamId;
    private $minTime;
    private $maxTime;

    public function getDatePeriod(): ?\DatePeriod
    {
        return $this->datePeriod;
    }

    public function setDatePeriod(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $this->datePeriod = new \DatePeriod($dateFrom, new \DateInterval("P2Y"), $dateTo);
    }

    public function getLeagueId()
    {
        return $this->leagueId;
    }

    public function setLeagueId(int $leagueId): void
    {
        $this->leagueId = $leagueId;
    }

    public function getGoals(): ?array
    {
        return [
            $this->minGoals,
            $this->maxGoals
        ];
    }

    public function setGoals(int $min = null, int $max = null)
    {
        $this->minGoals = $min;
        $this->maxGoals = $max;
    }

    public function getTeamId(): ?int
    {
        return $this->teamId;
    }

    public function setTeamId(?int $teamId): void
    {
        $this->teamId = $teamId;
    }

    public function getTime(): array 
    {
        return [
            $this->minTime,
            $this->maxTime
        ];
    }

    public function setTime(int $min, int $max): void
    {
        $this->minTime = $min;
        $this->maxTime = $max;
    }
}
