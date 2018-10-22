<?php

namespace App\Type\SeekCriteria;

class SeekCriteria
{
    private $datePeriod;

    public function getDatePeriod(): \DatePeriod
    {
        return $this->datePeriod;
    }

    public function setDatePeriod(\DatePeriod $datePeriod)
    {
        $this->datePeriod = $datePeriod;
    }
}
