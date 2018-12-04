<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
ini_set("allow_url_fopen", 1);
ini_set("allow_url_include", 1);
define("WS_PATH", '../webservices/');

require_once WS_PATH . 'webservice_handler/factory.php';
include_once 'LocadoraResponse.php';


if(isset($_GET['loc']) && $_GET['loc'] != ''){
    $a = WebService_Handler_Factory::create($_GET['loc']);
    $b = array(
        "dataRetirada" 			=>  $_GET['dtreti'],
        "dataDevolucao" 		=>  $_GET['dtdevo'],
        "locadora_short" 		=>  $_GET['loc'],
        "cidadeRetirada"		=>  $_GET['iatareti'],
        "cidadeDevolucao"		=>  $_GET['iatadevo'],
        "loc" 					=>  $_GET['rdcar'],
        "sipp"                  =>  '',
        "promocod"				=>  $_GET['promo'],
        "idReti"                =>  $_GET['reti'],
        "idDevo"                =>  $_GET['devo']
    );
    header('Content-Type: application/json; charset=utf-8');
    $pesquisa = $a->setData($b)->pesquisar();
    //var_dump($pesquisa);
    $rs = new LocadoraResponse();
    $rs->setPickupDateTime($pesquisa->arr_dados["PickUpDateTime"])
        ->setReturnDateTime($pesquisa->arr_dados["ReturnDateTime"]);
    if(is_array($pesquisa->arr_dados["vendoravail"])){
        foreach($pesquisa->arr_dados["vendoravail"] as $avail){
            $vnd = new VendorAvail();
            if($avail['VehicleCharges']){
                foreach($avail['VehicleCharges'] as $charge){
                    $ch = new VehicleCharges();
                    $ch->setVehicleChargeIncludedInRate($charge['VehicleCharge_IncludedInRate'])
                        ->setVehicleChargeRateConvertInd($charge['VehicleCharge_RateConvertInd'])
                        ->setVehicleChargeIncludedInEstTotalInd($charge['VehicleCharge_IncludedInEstTotalInd'])
                        ->setVehicleChargeDescription($charge['VehicleCharge_Description'])
                        ->setVehicleChargeDecimalPlaces($charge['VehicleCharge_DecimalPlaces'])
                        ->setVehicleChargeCurrencyCode($charge['VehicleCharge_CurrencyCode'])
                        ->setVehicleChargeTaxInclusive($charge['VehicleCharge_TaxInclusive'])
                        ->setVehicleChargeAmount($charge['VehicleCharge_Amount'])
                        ->setVehicleChargePurpose($charge['VehicleCharge_Purpose'])
                        ->setCalculationUnitCharge($charge['Calculation_UnitCharge'])
                        ->setCalculationUnitName($charge['Calculation_UnitName'])
                        ->setCalculationQuantity($charge['Calculation_Quantity'])
                        ->setCalculationTotal($charge['Calculation_Total']);
                    $vnd->setVehicleCharges($ch);
                }
            }
            if($avail['Fees']){
                foreach($avail['Fees'] as $fee){
                    $fe = new Fee();
                    $fe->setIncludedInRate($fee['IncludedInRate'])
                        ->setRateConvertInd($fee['RateConvertInd'])
                        ->setIncludedInEstTotalInd($fee['IncludedInEstTotalInd'])
                        ->setDescription($fee['Description'])
                        ->setDecimalPlaces($fee['DecimalPlaces'])
                        ->setCurrencyCode($fee['CurrencyCode'])
                        ->setTaxInclusive($fee['TaxInclusive'])
                        ->setAmount($fee['Amount'])
                        ->setPurpose($fee['Purpose'])
                        ->setPercentage($fee['Percentage'])
                        ->setTotal($fee['Total']);
                    $vnd->setFees($fe);
                }
            }
            if($avail['Coverage']){
                foreach($avail['Coverage'] as $cover){
                    $cov = new Coverage();
                    $cov->setCoverageType($cover['CoverageType'])
                        ->setDetails($cover['Details'])
                        ->setUnitCharge($cover['UnitCharge'])
                        ->setQuantity($cover['Quantity'])
                        ->setTotal($cover['Total'])
                        ->setIncluded($cover['Included']);
                    $vnd->setCoverage($cov);
                }
            }
            $vnd->setCodeXml($avail['CodeXml'])
                ->setPickupLocation($avail['PickUpLocation'])
                ->setReturnLocation($avail['ReturnLocation'])
                ->setIdPickupLocation($avail['IdPickUpLocation'])
                ->setIdReturnLocation($avail['IdReturnLocation'])
                ->setAeroReti($_GET['aeroreti'])
                ->setAeroDevo($_GET['aerodevo'])
                ->setRatePeriod($avail['RatePeriod'])
                ->setRateCategory($avail['RateCategory'])
                ->setAirConditionInd($avail['AirConditionInd'])
                ->setTransmissionType($avail['TransmissionType'])
                ->setDescription($avail['Description'])
                ->setBaggageQuantity($avail['BaggageQuantity'])
                ->setPassengerQuantity($avail['PassengerQuantity'])
                ->setCode($avail['Code'])
                ->setVehicleCategory($avail['VehicleCategory'])
                ->setDoorCount($avail['DoorCount'])
                ->setSize($avail['Size'])
                ->setModelo($avail['Modelo'])
                ->setRateDistanceUnlimited($avail['RateDistance_Unlimited'])
                ->setRateDistanceDistUnitName($avail['RateDistance_DistUnitName'])
                ->setRateDistanceVehiclePeriodUnitName($avail['RateDistance_VehiclePeriodUnitName'])
                ->setRateDistanceQuantity($avail['RateDistance_Quantity'])
                ->setRateRatePeriod($avail['Rate_RatePeriod'])
                ->setRateRateQualifier($avail['Rate_RateQualifier'])
                ->setRateRateCategory($avail['Rate_RateCategory'])
                ->setMinimumDayInd($avail['MinimumDayInd'])
                ->setMaximumDayInd($avail['MaximumDayInd'])
                ->setTotalChargeRateTotalAmount($avail['TotalCharge_RateTotalAmount'])
                ->setTotalChargeEstimatedTotalAmount($avail['TotalCharge_EstimatedTotalAmount'])
                ->setTotalChargeCurrencyCode($avail['TotalCharge_CurrencyCode'])
                ->setTotalChargeDecimalPlaces($avail['TotalCharge_DecimalPlaces'])
                ->setTotalChargeRateConvertInd($avail['TotalCharge_RateConvertInd'])
                ->setReferencesId($avail['References_ID'])
                ->setReferencesType($avail['References_Type']);

            $rs->setVendorAvail($vnd);
        }
    }
    echo $rs->getJson();
}