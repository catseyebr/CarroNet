<?php

/**
 * VehicleCharges short summary.
 *
 * VehicleCharges description.
 *
 * @version 1.0
 * @author Eduardo Assis
 */
class VehicleCharges
{
    private $vehicleChargeIncludedInRate;
    private $vehicleChargeRateConvertInd;
    private $vehicleChargeIncludedInEstTotalInd;
    private $vehicleChargeDescription;
    private $vehicleChargeDecimalPlaces;
    private $vehicleChargeCurrencyCode;
    private $vehicleChargeTaxInclusive;
    private $vehicleChargeAmount;
    private $vehicleChargePurpose;
    private $calculationUnitCharge;
    private $calculationUnitName;
    private $calculationQuantity;
    private $calculationTotal;

    public function getVehicleChargeIncludedInRate()
    {
        return $this->vehicleChargeIncludedInRate;
    }

    public function setVehicleChargeIncludedInRate($value)
    {
        $this->vehicleChargeIncludedInRate = $value;
        return $this;
    }

    public function getVehicleChargeRateConvertInd()
    {
        return $this->vehicleChargeRateConvertInd;
    }

    public function setVehicleChargeRateConvertInd($value)
    {
        $this->vehicleChargeRateConvertInd = $value;
        return $this;
    }

    public function getVehicleChargeIncludedInEstTotalInd()
    {
        return $this->vehicleChargeIncludedInEstTotalInd;
    }

    public function setVehicleChargeIncludedInEstTotalInd($value)
    {
        $this->vehicleChargeIncludedInEstTotalInd = $value;
        return $this;
    }

    public function getVehicleChargeDescription()
    {
        return $this->vehicleChargeDescription;
    }

    public function setVehicleChargeDescription($value)
    {
        $this->vehicleChargeDescription = $value;
        return $this;
    }

    public function getVehicleChargeDecimalPlaces()
    {
        return $this->vehicleChargeDecimalPlaces;
    }

    public function setVehicleChargeDecimalPlaces($value)
    {
        $this->vehicleChargeDecimalPlaces = $value;
        return $this;
    }

    public function getVehicleChargeCurrencyCode()
    {
        return $this->vehicleChargeCurrencyCode;
    }

    public function setVehicleChargeCurrencyCode($value)
    {
        $this->vehicleChargeCurrencyCode = $value;
        return $this;
    }

    public function getVehicleChargeTaxInclusive()
    {
        return $this->vehicleChargeTaxInclusive;
    }

    public function setVehicleChargeTaxInclusive($value)
    {
        $this->vehicleChargeTaxInclusive = $value;
        return $this;
    }

    public function getVehicleChargeAmount()
    {
        return $this->vehicleChargeAmount;
    }

    public function setVehicleChargeAmount($value)
    {
        $this->vehicleChargeAmount = $value;
        return $this;
    }

    public function getVehicleChargePurpose()
    {
        return $this->vehicleChargePurpose;
    }

    public function setVehicleChargePurpose($value)
    {
        $this->vehicleChargePurpose = $value;
        return $this;
    }

    public function getCalculationUnitCharge()
    {
        return $this->calculationUnitCharge;
    }

    public function setCalculationUnitCharge($value)
    {
        $this->calculationUnitCharge = $value;
        return $this;
    }

    public function getCalculationUnitName()
    {
        return $this->calculationUnitName;
    }

    public function setCalculationUnitName($value)
    {
        $this->calculationUnitName = $value;
        return $this;
    }

    public function getCalculationQuantity()
    {
        return $this->calculationQuantity;
    }

    public function setCalculationQuantity($value)
    {
        $this->calculationQuantity = $value;
        return $this;
    }

    public function getCalculationTotal()
    {
        return $this->calculationTotal;
    }

    public function setCalculationTotal($value)
    {
        $this->calculationTotal = $value;
        return $this;
    }
}