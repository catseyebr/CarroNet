<?php

/**
 * Fee short summary.
 *
 * Fee description.
 *
 * @version 1.0
 * @author Eduardo Assis
 */
class Fee
{
    private $includedInRate;
    private $rateConvertInd;
    private $includedInEstTotalInd;
    private $description;
    private $decimalPlaces;
    private $currencyCode;
    private $taxInclusive;
    private $amount;
    private $purpose;
    private $percentage;
    private $total;

    public function getIncludedInRate()
    {
        return $this->includedInRate;
    }

    public function setIncludedInRate($value)
    {
        $this->includedInRate = $value;
        return $this;
    }

    public function getRateConvertInd()
    {
        return $this->rateConvertInd;
    }

    public function setRateConvertInd($value)
    {
        $this->rateConvertInd = $value;
        return $this;
    }

    public function getIncludedInEstTotalInd()
    {
        return $this->includedInEstTotalInd;
    }

    public function setIncludedInEstTotalInd($value)
    {
        $this->includedInEstTotalInd = $value;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
        return $this;
    }

    public function getDecimalPlaces()
    {
        return $this->decimalPlaces;
    }

    public function setDecimalPlaces($value)
    {
        $this->decimalPlaces = $value;
        return $this;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode($value)
    {
        $this->currencyCode = $value;
        return $this;
    }

    public function getTaxInclusive()
    {
        return $this->taxInclusive;
    }

    public function setTaxInclusive($value)
    {
        $this->taxInclusive = $value;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($value)
    {
        $this->amount = $value;
        return $this;
    }

    public function getPurpose()
    {
        return $this->purpose;
    }

    public function setPurpose($value)
    {
        $this->purpose = $value;
        return $this;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setPercentage($value)
    {
        $this->percentage = $value;
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
}