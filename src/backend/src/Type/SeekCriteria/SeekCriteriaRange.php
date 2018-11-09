<?php

namespace App\Type\SeekCriteria;

class SeekCriteriaRange {
    
    public $min;
    public $max;

    public function __construct($min, $max)
    {
        if(is_null($min) && is_null($max)) {
            throw new SeekCriteriaException(("Invalid range. At least one of `min` or `max` shold be defined."));
        }
        
        $this->min = $min;
        $this->max = $max;
    }
}