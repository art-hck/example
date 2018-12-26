<?php

namespace App\Type\SeekCriteria\Types;

use App\Type\SeekCriteria\SeekCriteria;
use App\Type\SeekCriteria\SeekCriteriaRange;

class SeekCriteriaTransferFilter extends SeekCriteria
{
    public const orderByFields = ["id", "date", "fee", "mv"];

    private $datePeriod;
    private $feeRange;
    private $mvRange;
    private $teamId;
    private $leagueId;

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

    public function getFeeRange(): ?SeekCriteriaRange
    {
        return $this->feeRange;
    }

    public function setFeeRange(?SeekCriteriaRange $feeRange): self
    {
        $this->feeRange = $feeRange;

        return $this;
    }

    public function getMvRange(): ?SeekCriteriaRange
    {
        return $this->mvRange;
    }

    public function setMvRange(?SeekCriteriaRange $mvRange): self
    {
        $this->mvRange = $mvRange;

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

    static function getOrderByFields(): array
    {
        return self::orderByFields;
    }
}