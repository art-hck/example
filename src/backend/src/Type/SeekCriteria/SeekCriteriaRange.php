<?php

namespace App\Type\SeekCriteria;

class SeekCriteriaRange {
    
    public $min;
    public $max;

    public function __construct($min, $max)
    {
        if(!$min && !$max) {
            throw new SeekCriteriaException(("Invalid range. At least one of `min` or `max` shold be defined."));
        }
        
        $this->min = $min;
        $this->max = $max;
    }
}