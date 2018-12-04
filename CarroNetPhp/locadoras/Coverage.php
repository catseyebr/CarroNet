<?php

/**
 * Coverage short summary.
 *
 * Coverage description.
 *
 * @version 1.0
 * @author eduar
 */
class Coverage
{
    private $coverageType;
    private $details;
    private $unitCharge;
    private $quantity;
    private $total;
    private $included;

    public function getCoverageType()
    {
        return $this->coverageType;
    }

    public function setCoverageType($value)
    {
        $this->coverageType = $value;
        return $this;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setDetails($value)
    {
        $this->details = $value;
        return $this;
    }

    public function getUnitCharge()
    {
        return $this->unitCharge;
    }

    public function setUnitCharge($value)
    {
        $this->unitCharge = $value;
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($value)
    {
        $this->quantity = $value;
        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($value)
    {
        $this->total = $value;
        return $this;
    }

    public function getIncluded()
    {
        return $this->included;
    }

    public function setIncluded($value)
    {
        $this->included = $value;
        return $this;
    }
}