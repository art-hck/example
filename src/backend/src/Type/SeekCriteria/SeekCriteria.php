<?php

namespace App\Type\SeekCriteria;

abstract class SeekCriteria
{
    private $orderBy;
    private $orderDirection;
    private $offset;
    private $limit;

    public const validOrderDirections = ["ASC", "DESC"];
    
    abstract static function getOrderByFields() : array;

    public function getOrderBy(): ?string 
    {
        return $this->orderBy;
    }

    public function setOrderBy(?string $orderBy): self
    {
        if(!in_array($orderBy, $this->getOrderByFields())) {
            throw new SeekCriteriaException(
                "Invalid `orderBy` (${orderBy}). Available values: `" . implode("`, `", $this->getOrderByFields()) . "`"
            );
        }
        
        $this->orderBy = $orderBy;

        return $this;
    }

    public function getOrderDirection(): ?string 
    {
        return $this->orderDirection;
    }

    public function setOrderDirection($orderDirection): self
    {
        if(!in_array($orderDirection, self::validOrderDirections)) {
            throw new SeekCriteriaException(
                "Invalid `orderDirection` (${orderDirection}). Available values: `" . implode("`, `", self::validOrderDirections) . "`"
            );
        }
        
        $this->orderDirection = $orderDirection;

        return $this;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function setOffset($offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit($limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}