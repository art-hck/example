<?php

namespace App\Type\SeekCriteria\Types;

use App\Type\PlayerRole\PlayerRole;
use App\Type\SeekCriteria\SeekCriteria;
use App\Type\SeekCriteria\SeekCriteriaRange;

class SeekCriteriaTransferFilter extends SeekCriteria
{
    public const orderByFields = ["id", "date", "fee", "mv"];

    private $datePeriod;
    private $feeRange;
    private $mvRange;

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


    static function getOrderByFields(): array
    {
        return self::orderByFields;
    }
}
