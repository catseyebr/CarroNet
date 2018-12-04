<?php
include_once 'VendorAvail.php';
/**
 * LocadoraResponse short summary.
 *
 * LocadoraResponse description.
 *
 * @version 1.0
 * @author Eduardo Assis
 */
class LocadoraResponse
{
    /**
     * Summary of $pickupDateTime
     * @var string
     */
    private $pickupDateTime;
    /**
     * Summary of $returnDatetime
     * @var string
     */
    private $returnDatetime;
    /**
     * Summary of $vendorAvail
     * @var VendorAvail[]
     */
    private $vendorAvail;

    public function getJson()
    {
        $return = [
                'PickupDateTime' => $this->getPickupDateTime(),
                'ReturnDatetime' => $this->getReturnDateTime()

        ];
        if(is_array($this->getVendorAvail())){
            foreach($this->getVendorAvail() as $vnd){
                $ch = [];
                $fe = [];
                $cov = [];
                if(is_array($vnd->getVehicleCharges())){
                    foreach($vnd->getVehicleCharges() as $charge){
                        $ch[$charge->getVehicleChargePurpose()] = [
                            'VehicleCharge_IncludedInRate' => $charge->getVehicleChargeIncludedInRate(),
                            'VehicleCharge_RateConvertInd' => $charge->getVehicleChargeRateConvertInd(),
                            'VehicleCharge_IncludedInEstTotalInd' => $charge->getVehicleChargeIncludedInEstTotalInd(),
                            'VehicleCharge_Description' => $charge->getVehicleChargeDescription(),
                            'VehicleCharge_DecimalPlaces' => $charge->getVehicleChargeDecimalPlaces(),
                            'VehicleCharge_CurrencyCode' => $charge->getVehicleChargeCurrencyCode(),
                            'VehicleCharge_TaxInclusive' => $charge->getVehicleChargeTaxInclusive(),
                            'VehicleCharge_Amount' => $charge->getVehicleChargeAmount(),
                            'VehicleCharge_Purpose' => $charge->getVehicleChargePurpose(),
                            'Calculation_UnitCharge' => $charge->getCalculationUnitCharge(),
                            'Calculation_UnitName' => $charge->getCalculationUnitName(),
                            'Calculation_Quantity' => $charge->getCalculationQuantity(),
                            'Calculation_Total' => $charge->getCalculationTotal(),
                        ];
                    }
                }
                if(is_array($vnd->getFees())){
                    foreach($vnd->getFees() as $fee){
                        $fe[$fee->getPurpose()] =[
                            'IncludedInRate' => $fee->getIncludedInRate(),
                            'RateConvertInd' => $fee->getRateConvertInd(),
                            'IncludedInEstTotalInd' => $fee->getIncludedInEstTotalInd(),
                            'Description' => $fee->getDescription(),
                            'DecimalPlaces' => $fee->getDecimalPlaces(),
                            'CurrencyCode' => $fee->getCurrencyCode(),
                            'TaxInclusive' => $fee->getTaxInclusive(),
                            'Amount' => $fee->getAmount(),
                            'Purpose' => $fee->getPurpose(),
                            'Percentage' => $fee->getPercentage(),
                            'Total' => $fee->getTotal(),
                        ];
                    }
                }
                if(is_array($vnd->getCoverage())){
                    foreach($vnd->getCoverage() as $cover){
                        $cov[$cover->getCoverageType()] =[
                            'CoverageType' => $cover->getCoverageType(),
                            'Details' => NULL,
                            'UnitCharge' => $cover->getUnitCharge(),
                            'Quantity' => $cover->getQuantity(),
                            'Total' => $cover->getTotal(),
                            'Included' => $cover->getIncluded(),
                        ];
                    }
                }
                $return['VendorAvail'][$vnd->getCodeXml()] = [
                    'CodeXml' => $vnd->getCodeXml(),
                    'PickupLocation' => $vnd->getPickupLocation(),
                    'ReturnLocation' => $vnd->getReturnLocation(),
                    'IdPickupLocation' => $vnd->getIdPickupLocation(),
                    'IdReturnLocation' => $vnd->getIdReturnLocation(),
                    'AeroReti' => $vnd->getAeroReti(),
                    'AeroDevo'  => $vnd->getAeroDevo(),
                    'RatePeriod' => $vnd->getRatePeriod(),
                    'RateCategory' => $vnd->getRateCategory(),
                    'AirConditionInd' => $vnd->getAirConditionInd(),
                    'TransmissionType' => $vnd->getTransmissionType(),
                    'Description' => $vnd->getDescription(),
                    'BaggageQuantity' => $vnd->getBaggageQuantity(),
                    'PassengerQuantity' => $vnd->getPassengerQuantity(),
                    'Code' => $vnd->getCode(),
                    'VehicleCategory' => $vnd->getVehicleCategory(),
                    'DoorCount' => $vnd->getDoorCount(),
                    'Size' => $vnd->getSize(),
                    'Modelo' => $vnd->getModelo(),
                    'RateDistance_Unlimited' => $vnd->getRateDistanceUnlimited(),
                    'RateDistance_DistUnitName' => $vnd->getRateDistanceDistUnitName(),
                    'RateDistance_VehiclePeriodUnitName' => $vnd->getRateDistanceVehiclePeriodUnitName(),
                    'RateDistance_Quantity' => $vnd->getRateDistanceQuantity(),
                    'Rate_RatePeriod' => $vnd->getRateRatePeriod(),
                    'Rate_RateQualifier' => $vnd->getRateRateQualifier(),
                    'Rate_RateCategory' => $vnd-> getRateRateCategory(),
                    'MinimumDayInd' => $vnd->getMinimumDayInd(),
                    'MaximumDayInd' => $vnd->getMaximumDayInd(),
                    'TotalCharge_RateTotalAmount' => $vnd->getTotalChargeRateTotalAmount(),
                    'TotalCharge_EstimatedTotalAmount' => $vnd->getTotalChargeEstimatedTotalAmount(),
                    'TotalCharge_CurrencyCode' => $vnd->getTotalChargeCurrencyCode(),
                    'TotalCharge_DecimalPlaces' => $vnd->getTotalChargeDecimalPlaces(),
                    'TotalCharge_RateConvertInd' => $vnd->getTotalChargeRateConvertInd(),
                    'VehicleCharges' => $ch,
                    'Fees' => $fe,
                    'References_ID' => $vnd->getReferencesId(),
                    'References_Type' => $vnd->getReferencesType(),
                    'Coverage' => $cov
                ];
            }
        }
        return json_encode($return);
    }

    public function getPickupDateTime()
    {
        return $this->pickupDateTime;
    }

    public function setPickupDateTime($value)
    {
        $this->pickupDateTime = $value;
        return $this;
    }

    public function getReturnDateTime()
    {
        return $this->returnDatetime;
    }

    public function setReturnDateTime($value)
    {
        $this->returnDatetime = $value;
        return $this;
    }

    /**
     * Summary of getVendorAvail
     * @return VendorAvail[]
     */
    public function getVendorAvail()
    {
        return $this->vendorAvail;
    }

    /**
     * Summary of setVendorAvail
     * @param VendorAvail $value
     * @return LocadoraResponse
     */
    public function setVendorAvail($value)
    {
        $this->vendorAvail[] = $value;
        return $this;
    }
}