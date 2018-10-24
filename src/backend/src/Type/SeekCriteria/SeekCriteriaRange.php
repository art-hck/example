<?php

namespace App\Type\SeekCriteria;

class SeekCriteriaRange {
    
    public $min;
    public $max;

    public function __construct(?int $min, ?int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }
}