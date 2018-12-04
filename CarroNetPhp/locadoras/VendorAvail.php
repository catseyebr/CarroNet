<?php
include_once 'VehicleCharges.php';
include_once 'Fee.php';
include_once 'Coverage.php';
/**
 * VendorAvail short summary.
 *
 * VendorAvail description.
 *
 * @version 1.0
 * @author Eduardo Assis
 */
class VendorAvail
{
    private $codeXml;
    private $pickupLocation;
    private $returnLocation;
    private $idPickupLocation;
    private $idReturnLocation;
    /**
     * Summary of $aeroReti
     * @var bool
     */
    private $aeroReti;
    /**
     * Summary of $aeroDevo
     * @var bool
     */
    private $aeroDevo;
    private $ratePeriod;
    private $rateCategory;
    private $airConditionInd;
    private $transmissionType;
    private $description;
    private $baggageQuantity;
    private $passengerQuantity;
    private $code;
    private $vehicleCategory;
    private $doorCount;
    private $size;
    private $modelo;
    private $rateDistanceUnlimited;
    private $rateDistanceDistUnitName;
    private $rateDistanceVehiclePeriodUnitName;
    private $rateDistanceQuantity;
    /**
     * Summary of $vehicleCharges
     * @var VehicleCharges[]
     */
    private $vehicleCharges;
    private $rateRatePeriod;
    private $rateRateQualifier;
    private $rateRateCategory;
    private $minimumDayInd;
    private $maximumDayInd;
    private $totalChargeRateTotalAmount;
    private $totalChargeEstimatedTotalAmount;
    private $totalChargeCurrencyCode;
    private $totalChargeDecimalPlaces;
    private $totalChargeRateConvertInd;
    /**
     * Summary of $fees
     * @var Fee[]
     */
    private $fees;
    private $referencesId;
    private $referencesType;
    /**
     * Summary of $coverage
     * @var Coverage[]
     */
    private $coverage;

    public function getCodeXml()
    {
        return $this->codeXml;
    }

    public function setCodeXml($value)
    {
        $this->codeXml = $value;
        return $this;
    }

    public function getPickupLocation()
    {
        return $this->pickupLocation;
    }

    public function setPickupLocation($value)
    {
        $this->pickupLocation = $value;
        return $this;
    }

    public function getReturnLocation()
    {
        return $this->returnLocation;
    }

    public function setReturnLocation($value)
    {
        $this->returnLocation = $value;
        return $this;
    }

    public function getIdPickupLocation()
    {
        return $this->idPickupLocation;
    }

    public function setIdPickupLocation($value)
    {
        $this->idPickupLocation = $value;
        return $this;
    }

    public function getIdReturnLocation()
    {
        return $this->idReturnLocation;
    }

    public function setIdReturnLocation($value)
    {
        $this->idReturnLocation = $value;
        return $this;
    }

    public function getAeroReti()
    {
        return $this->aeroReti;
    }

    public function setAeroReti($value)
    {
        $this->aeroReti = $value;
        return $this;
    }

    public function getAeroDevo()
    {
        return $this->aeroDevo;
    }

    public function setAeroDevo($value)
    {
        $this->aeroDevo = $value;
        return $this;
    }

    public function getRatePeriod()
    {
        return $this->ratePeriod;
    }

    public function setRatePeriod($value)
    {
        $this->ratePeriod = $value;
        return $this;
    }

    public function getRateCategory()
    {
        return $this->rateCategory;
    }

    public function setRateCategory($value)
    {
        $this->rateCategory = $value;
        return $this;
    }

    public function getAirConditionInd()
    {
        return $this->airConditionInd;
    }

    public function setAirConditionInd($value)
    {
        $this->airConditionInd = $value;
        return $this;
    }

    public function getTransmissionType()
    {
        return $this->transmissionType;
    }

    public function setTransmissionType($value)
    {
        $this->transmissionType = $value;
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

    public function getBaggageQuantity()
    {
        return $this->baggageQuantity;
    }

    public function setBaggageQuantity($value)
    {
        $this->baggageQuantity = $value;
        return $this;
    }

    public function getPassengerQuantity()
    {
        return $this->passengerQuantity;
    }

    public function setPassengerQuantity($value)
    {
        $this->passengerQuantity = $value;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($value)
    {
        $this->code = $value;
        return $this;
    }

    public function getVehicleCategory()
    {
        return $this->vehicleCategory;
    }

    public function setVehicleCategory($value)
    {
        $this->vehicleCategory = $value;
        return $this;
    }

    public function getDoorCount()
    {
        return $this->doorCount;
    }

    public function setDoorCount($value)
    {
        $this->doorCount = $value;
        return $this;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($value)
    {
        $this->size = $value;
        return $this;
    }

    public function getModelo()
    {
        return $this->modelo;
    }

    public function setModelo($value)
    {
        $this->modelo = $value;
        return $this;
    }

    public function getRateDistanceUnlimited()
    {
        return $this->rateDistanceUnlimited;
    }

    public function setRateDistanceUnlimited($value)
    {
        $this->rateDistanceUnlimited = $value;
        return $this;
    }

    public function getRateDistanceDistUnitName()
    {
        return $this->rateDistanceDistUnitName;
    }

    public function setRateDistanceDistUnitName($value)
    {
        $this->rateDistanceDistUnitName = $value;
        return $this;
    }

    public function getRateDistanceVehiclePeriodUnitName()
    {
        return $this->rateDistanceVehiclePeriodUnitName;
    }

    public function setRateDistanceVehiclePeriodUnitName($value)
    {
        $this->rateDistanceVehiclePeriodUnitName = $value;
        return $this;
    }

    public function getRateDistanceQuantity()
    {
        return $this->rateDistanceQuantity;
    }

    public function setRateDistanceQuantity($value)
    {
        $this->rateDistanceQuantity = $value;
        return $this;
    }

    /**
     * Summary of getVehicleCharges
     * @return VehicleCharges[]
     */
    public function getVehicleCharges()
    {
        return $this->vehicleCharges;
    }

    /**
     * Summary of setVehicleCharges
     * @param VehicleCharges $value
     * @return VendorAvail
     */
    public function setVehicleCharges($value)
    {
        $this->vehicleCharges[] = $value;
        return $this;
    }

    public function getRateRatePeriod()
    {
        return $this->rateRatePeriod;
    }

    public function setRateRatePeriod($value)
    {
        $this->rateRatePeriod = $value;
        return $this;
    }

    public function getRateRateQualifier()
    {
        return $this->rateRateQualifier;
    }

    public function setRateRateQualifier($value)
    {
        $this->rateRateQualifier = $value;
        return $this;
    }

    public function getRateRateCategory()
    {
        return $this->rateRateCategory;
    }

    public function setRateRateCategory($value)
    {
        $this->rateRateCategory = $value;
        return $this;
    }

    public function getMinimumDayInd()
    {
        return $this->minimumDayInd;
    }

    public function setMinimumDayInd($value)
    {
        $this->minimumDayInd = $value;
        return $this;
    }

    public function getMaximumDayInd()
    {
        return $this->maximumDayInd;
    }

    public function setMaximumDayInd($value)
    {
        $this->maximumDayInd = $value;
        return $this;
    }

    public function getTotalChargeRateTotalAmount()
    {
        return $this->totalChargeRateTotalAmount;
    }

    public function setTotalChargeRateTotalAmount($value)
    {
        $this->totalChargeRateTotalAmount = $value;
        return $this;
    }

    public function getTotalChargeEstimatedTotalAmount()
    {
        return $this->totalChargeEstimatedTotalAmount;
    }

    public function setTotalChargeEstimatedTotalAmount($value)
    {
        $this->totalChargeEstimatedTotalAmount = $value;
        return $this;
    }

    public function getTotalChargeCurrencyCode()
    {
        return $this->totalChargeCurrencyCode;
    }

    public function setTotalChargeCurrencyCode($value)
    {
        $this->totalChargeCurrencyCode = $value;
        return $this;
    }

    public function getTotalChargeDecimalPlaces()
    {
        return $this->totalChargeDecimalPlaces;
    }

    public function setTotalChargeDecimalPlaces($value)
    {
        $this->totalChargeDecimalPlaces = $value;
        return $this;
    }

    public function getTotalChargeRateConvertInd()
    {
        return $this->totalChargeRateConvertInd;
    }

    public function setTotalChargeRateConvertInd($value)
    {
        $this->totalChargeRateConvertInd = $value;
        return $this;
    }

    /**
     * Summary of getFees
     * @return Fee[]
     */
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * Summary of setFees
     * @param Fee $value
     * @return VendorAvail
     */
    public function setFees($value)
    {
        $this->fees[] = $value;
        return $this;
    }

    public function getReferencesId()
    {
        return $this->referencesId;
    }

    public function setReferencesId($value)
    {
        $this->referencesId = $value;
        return $this;
    }

    public function getReferencesType()
    {
        return $this->referencesType;
    }

    public function setReferencesType($value)
    {
        $this->referencesType = $value;
        return $this;
    }

    /**
     * Summary of getCoverage
     * @return Coverage[]
     */
    public function getCoverage()
    {
        return $this->coverage;
    }

    /**
     * Summary of setCoverage
     * @param Coverage $value
     * @return VendorAvail
     */
    public function setCoverage($value)
    {
        $this->coverage[] = $value;
        return $this;
    }
}