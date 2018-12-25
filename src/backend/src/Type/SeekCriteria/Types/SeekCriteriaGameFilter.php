<?php

namespace App\Type\SeekCriteria\Types;

use App\Form\Extension\Core\Type\SeekCriteriaRangeType;
use App\Type\PlayerRole\PlayerRole;
use App\Type\SeekCriteria\SeekCriteria;
use App\Type\SeekCriteria\SeekCriteriaRange;

class SeekCriteriaGameFilter extends SeekCriteria
{
    public const orderByFields = ["id", "date"];

    private $datePeriod;
    private $teamId;
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

    static function getOrderByFields(): array
    {
        return self::orderByFields;
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
}
