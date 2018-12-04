<?php

require_once WS_PATH . 'xml_handler.php';

Final Class XML_Handler_Hertzbr extends XML_Handler {
  //protected $_a = 'teste';

  public function __construct ($received_data, $request_data, $parser_data) {
	  $this->_haw_received_data = $received_data;
	  $this->_haw_request_data  = $request_data;
      $this->_parser_data = $parser_data;

	  /**
	   * Troca padrão de codificação SOAP para padrão de codificação XML aceitável em simplexml_load_string();
	   */
      //$data = str_replace('<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"><s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">', "",$received_data); //<?php
      //$data = str_replace('</s:Body></s:Envelope>', "",$data); //<?php
	  $patterns = array();
	  $patterns[0] = '/<s:/';
	  $patterns[1] = '/<\/s:/';
	  $replacements = array();
	  $replacements[2] = '<';
	  $replacements[1] = '</';
	  $data = preg_replace($patterns, $replacements, $received_data);
	  $receivedata = simplexml_load_string($data);

	  $received = (is_object($receivedata))?$receivedata->Body:NULL;

	  //var_dump($received);
	  if (isset($received->OTA_VehAvailRateResponse->OTA_VehAvailRateRS->VehAvailRSCore)) {
		  $this->_type = 'pesquisar';
		  $this->parsePesquisar($received);
	  } else if (isset($received->OTA_VehResResponse->OTA_VehResRS->VehResRSCore)) {
		  $this->_type = 'reservar';
		  $this->parseReservar($received);
	  } else if (isset($received->OTA_VehRetResResponse->OTA_VehRetResRS->VehRetResRSCore)) {
		  $this->_type = 'visualizar';
		  $this->parseVisualizar($received);
	  } else if (isset($received->OTA_VehCancelResponse->OTA_VehCancelRS)) {
		  $this->_type = 'cancelar';
		  $this->parseCancelar($received);
	  } else if (isset($received->OTA_VehLocSearchResponse->OTA_VehLocSearchRS)){
		  $this->_type = 'lojapesquisa';
		  $this->parseLojaPesquisa($received);
	  } else if (isset($received->OTA_VehLocDetailResponse->OTA_VehLocDetailRS)){
		  $this->_type = 'lojas';
		  $this->parseLojas($received);
	  } else {
		  $this->_is_ok = 0;
	  }
  }

  private function parsePesquisar ($received) {
      $this->_is_ok = 0;
    if (isset($received->OTA_VehAvailRateResponse->OTA_VehAvailRateRS->Success)) {
      if ($received->OTA_VehAvailRateResponse->OTA_VehAvailRateRS->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail->VehAvailCore["Status"] == "Available" || $received->OTA_VehAvailRateResponse->OTA_VehAvailRateRS->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail->VehAvailCore["Status"] ==  "OnRequest") {
		  $core = $received->OTA_VehAvailRateResponse->OTA_VehAvailRateRS->VehAvailRSCore;
			$this->_is_ok = 1;
		  	$rentalcore = get_object_vars($core->VehRentalCore);
		  	$this->_arr_dados['ReturnDateTime'] = $rentalcore['@attributes']['ReturnDateTime'];
		  	$this->_arr_dados['PickUpDateTime'] = $rentalcore['@attributes']['PickUpDateTime'];
		  	$pickuploc = get_object_vars($rentalcore['PickUpLocation']);
		  	$this->_arr_dados['PickUpLocation'] = $pickuploc['@attributes']['LocationCode'];
		  	$returnloc = get_object_vars($rentalcore['ReturnLocation']);
		  	$this->_arr_dados['ReturnLocation'] = $returnloc['@attributes']['LocationCode'];
		  	$vendoravails = NULL;
			foreach($core->VehVendorAvails->VehVendorAvail as $vendav){
				$vendavail = get_object_vars($vendav->VehAvails);
				$vehicleavail = get_object_vars($vendavail['VehAvail']->VehAvailCore);
				$vehicleavail_info = get_object_vars($vendavail['VehAvail']->VehAvailInfo);
				$coverage_info = $vehicleavail_info['PricedCoverages'];
				$vehicle = get_object_vars($vehicleavail['Vehicle']);
				$vehicle_type = get_object_vars($vehicle['VehType']);
				$vehicle_classe = get_object_vars($vehicle['VehClass']);
				$vehicle_modelo = ($vehicle['VehMakeModel'])?get_object_vars($vehicle['VehMakeModel']):NULL;
				$rentalrate = get_object_vars($vehicleavail['RentalRate']);
				$rentalrate_distance = get_object_vars($rentalrate['RateDistance']);
				$rentalrate_charges = $rentalrate['VehicleCharges'];
				$rentalrate_rate = get_object_vars($rentalrate['RateQualifier']);
				$rentalrate_restrictions = get_object_vars($rentalrate['RateRestrictions']);
				$totalrate = get_object_vars($vehicleavail['TotalCharge']);
				$taxas = $vehicleavail['Fees'];
				$taxas_arr = NULL;

				foreach($taxas->Fee as $txs){
					$taxas_unit = get_object_vars($txs);
					$calc_c = get_object_vars($txs->Calculation);

					$calc = ($calc_c)?$calc_c:NULL;
					$taxas_arr[$taxas_unit['@attributes']['Purpose']] = array(
						'IncludedInRate' => $taxas_unit['@attributes']['IncludedInRate'],
						'RateConvertInd' => $taxas_unit['@attributes']['RateConvertInd'],
						'IncludedInEstTotalInd' => $taxas_unit['@attributes']['IncludedInEstTotalInd'],
						'Description' => $taxas_unit['@attributes']['Description'],
						'DecimalPlaces' => $taxas_unit['@attributes']['DecimalPlaces'],
						'CurrencyCode' => $taxas_unit['@attributes']['CurrencyCode'],
						'TaxInclusive' => $taxas_unit['@attributes']['TaxInclusive'],
						'Amount' => $taxas_unit['@attributes']['Amount'],
						'Purpose' => $taxas_unit['@attributes']['Purpose'],
						'Percentage' => ($calc)?$calc['@attributes']['Percentage']:NULL,
						'Total' => ($calc)?$calc['@attributes']['Total']:NULL,
					);
				}
				$rates_arr = NULL;
                foreach($rentalrate_charges->VehicleCharge as $charge){
					$charge_unit = get_object_vars($charge);
					$rentalrate_calculation = get_object_vars($charge->Calculation);
					$rates_arr[$charge_unit['@attributes']['Purpose']] = array(
						'VehicleCharge_IncludedInRate' => $charge_unit['@attributes']['IncludedInRate'],
						'VehicleCharge_RateConvertInd' => $charge_unit['@attributes']['RateConvertInd'],
						'VehicleCharge_IncludedInEstTotalInd' => $charge_unit['@attributes']['IncludedInEstTotalInd'],
						'VehicleCharge_Description' => $charge_unit['@attributes']['Description'],
						'VehicleCharge_DecimalPlaces' => $charge_unit['@attributes']['DecimalPlaces'],
						'VehicleCharge_CurrencyCode' => $charge_unit['@attributes']['CurrencyCode'],
						'VehicleCharge_TaxInclusive' => $charge_unit['@attributes']['TaxInclusive'],
						'VehicleCharge_Amount' => $charge_unit['@attributes']['Amount'],
						'VehicleCharge_Purpose' => $charge_unit['@attributes']['Purpose'],
						'Calculation_UnitCharge' => $rentalrate_calculation['@attributes']['UnitCharge'],
						'Calculation_UnitName' => $rentalrate_calculation['@attributes']['UnitName'],
						'Calculation_Quantity' => $rentalrate_calculation['@attributes']['Quantity'],
						'Calculation_Total' => $rentalrate_calculation['@attributes']['Total']
					);
                    
				}
				$coverage_arr = NULL;
				foreach($coverage_info->PricedCoverage as $cover){
					$cover_unit = get_object_vars($cover);
					$coverage = get_object_vars($cover_unit['Coverage']);
					$coverage_charge = get_object_vars($cover_unit['Charge']);
					$coverage_calculation = get_object_vars($coverage_charge['Calculation']);
					$coverage_arr[$coverage['@attributes']['CoverageType']] = array(
						'CoverageType' => $coverage['@attributes']['CoverageType'],
						'Details' => $coverage['Details'],
						'UnitCharge'=> $coverage_calculation['@attributes']['UnitCharge'],
						'Total'=> $coverage_calculation['@attributes']['Total'],
					);
				}

				$code_xml = strtoupper($vehicle['@attributes']['Code']);
				$references = get_object_vars($vehicleavail['Reference']);
                $vendoravails[$code_xml]['CodeXml'] = $code_xml;
                $vendoravails[$code_xml]['PickUpLocation'] = $pickuploc['@attributes']['LocationCode'];
                $vendoravails[$code_xml]['ReturnLocation'] = $returnloc['@attributes']['LocationCode'];
                $vendoravails[$code_xml]['IdPickUpLocation'] = $this->_parser_data['idReti'];
                $vendoravails[$code_xml]['IdReturnLocation'] = $this->_parser_data['idDevo'];
				$vendoravails[$code_xml]['RatePeriod'] = $vendavail['@attributes']['RatePeriod'];
				$vendoravails[$code_xml]['RateCategory'] = $vendavail['@attributes']['RateCategory'];
				$vendoravails[$code_xml]['AirConditionInd'] = $vehicle['@attributes']['AirConditionInd'];
				$vendoravails[$code_xml]['TransmissionType'] = $vehicle['@attributes']['TransmissionType'];
				$vendoravails[$code_xml]['Description'] = $vehicle['@attributes']['Description'];
				$vendoravails[$code_xml]['BaggageQuantity'] = $vehicle['@attributes']['BaggageQuantity'];
				$vendoravails[$code_xml]['PassengerQuantity'] = $vehicle['@attributes']['PassengerQuantity'];
				$vendoravails[$code_xml]['Code'] = $vehicle['@attributes']['Code'];
				$vendoravails[$code_xml]['BaggageQuantity'] = $vehicle['@attributes']['BaggageQuantity'];
				$vendoravails[$code_xml]['VehicleCategory'] = $vehicle_type['@attributes']['VehicleCategory'];
				$vendoravails[$code_xml]['DoorCount'] = $vehicle_type['@attributes']['DoorCount'];
				$vendoravails[$code_xml]['Size'] = $vehicle_classe['@attributes']['Size'];
				$vendoravails[$code_xml]['Modelo'] = $vehicle_modelo['@attributes']['Name'];
				$vendoravails[$code_xml]['RateDistance_Unlimited'] = $rentalrate_distance['@attributes']['Unlimited'];
				$vendoravails[$code_xml]['RateDistance_DistUnitName'] = $rentalrate_distance['@attributes']['DistUnitName'];
				$vendoravails[$code_xml]['RateDistance_VehiclePeriodUnitName'] = $rentalrate_distance['@attributes']['VehiclePeriodUnitName'];
                $vendoravails[$code_xml]['RateDistance_Quantity'] = (isset($rentalrate_distance['@attributes']['Quantity']))?$rentalrate_distance['@attributes']['Quantity']:NULL;
				$vendoravails[$code_xml]['VehicleCharges'] = $rates_arr;
				$vendoravails[$code_xml]['Rate_RatePeriod'] = $rentalrate_rate['@attributes']['RatePeriod'];
				$vendoravails[$code_xml]['Rate_RateQualifier'] = $rentalrate_rate['@attributes']['RateQualifier'];
				$vendoravails[$code_xml]['Rate_RateCategory'] = $rentalrate_rate['@attributes']['RateCategory'];
				$vendoravails[$code_xml]['MinimumDayInd'] = $rentalrate_restrictions['@attributes']['MinimumDayInd'];
				$vendoravails[$code_xml]['MaximumDayInd'] = $rentalrate_restrictions['@attributes']['MaximumDayInd'];
				$vendoravails[$code_xml]['TotalCharge_RateTotalAmount'] = $totalrate['@attributes']['RateTotalAmount'];
				$vendoravails[$code_xml]['TotalCharge_EstimatedTotalAmount'] = $totalrate['@attributes']['EstimatedTotalAmount'];
				$vendoravails[$code_xml]['TotalCharge_CurrencyCode'] = $totalrate['@attributes']['CurrencyCode'];
				$vendoravails[$code_xml]['TotalCharge_DecimalPlaces'] = $totalrate['@attributes']['DecimalPlaces'];
				$vendoravails[$code_xml]['TotalCharge_RateConvertInd'] = $totalrate['@attributes']['RateConvertInd'];
				$vendoravails[$code_xml]['Fees'] = $taxas_arr;
				$vendoravails[$code_xml]['References_ID'] = $references['@attributes']['ID'];
				$vendoravails[$code_xml]['References_Type'] = $references['@attributes']['Type'];
				$vendoravails[$code_xml]['Coverage'] = $coverage_arr;
			}
		  	$this->_arr_dados['vendoravail'] = $vendoravails;
		}else{
		  $this->_is_ok = 0;
	  }

	}
  }

  private function parseReservar ($received) {
    if (isset($received->OTA_VehResResponse->OTA_VehResRS->Success) && ($received->OTA_VehResResponse->OTA_VehResRS->VehResRSCore['ReservationStatus'] == 'Reserved')) {
      $this->_is_ok = 1;
      $this->_xml_id = $received->OTA_VehResResponse->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
		$this->_diaria_sem_taxa = floatval($received->OTA_VehResResponse->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["RateTotalAmount"]);
		$this->_valor_total = floatval($received->OTA_VehResResponse->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["EstimatedTotalAmount"]);
		$this->_taxas_diaria = floatval($received->OTA_VehResResponse->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->TaxAmounts->TaxAmount["Total"]);
    } else if (isset($received->OTA_VehResResponse->OTA_VehResRS->Success) && ($received->OTA_VehResResponse->OTA_VehResRS->VehResRSCore['ReservationStatus'] == 'Pending')) {
		$this->_is_ok = 7;
		$this->_xml_id = $received->OTA_VehResResponse->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
		$this->_diaria_sem_taxa = floatval($received->OTA_VehResResponse->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["RateTotalAmount"]);
		$this->_valor_total = floatval($received->OTA_VehResResponse->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["EstimatedTotalAmount"]);
		$this->_taxas_diaria = floatval($received->OTA_VehResResponse->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->TaxAmounts->TaxAmount["Total"]);
	}else{
      $this->_is_ok = 0;
    }

  }

	private function parseVisualizar ($received) {
    	$this->_is_ok = 0;
    	if (isset($received->OTA_VehRetResResponse->OTA_VehRetResRS->Success)) {
	        $this->_is_ok = 1;
			$this->_status_reserva = $received->OTA_VehRetResResponse->OTA_VehRetResRS->VehRetResRSCore->VehReservation['ReservationStatus'];
			$this->_xml_id = $received->OTA_VehRetResResponse->OTA_VehRetResRS->VehRetResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
			if($this->_xml_id == ''){
				$this->_status_reserva = 'Cancelled';
			}
		}else{
			$this->parseError($received);
    	}
	}

	private function parseCancelar ($received) {
    	$this->_is_ok = 0;
		$this->_vis_errorTrack = 'Teste';
		if (isset($received->OTA_VehCancelResponse->OTA_VehCancelRS->Success)) 	{
        	$this->_is_ok = 1;
			$this->_vis_errorTrack = "Reserva cancelada com sucesso";
      	}else{
      		$this->parseError($received);
      	}
  	}

	private function parseLojaPesquisa ($received) {
		var_dump($received);
	}

	private function parseLojas ($received) {
		var_dump($received);
	}

	private function parseError ($received) {
    	$this->_is_ok = 0;
		$this->_vis_errorTrack = $received;
    }
}

?>