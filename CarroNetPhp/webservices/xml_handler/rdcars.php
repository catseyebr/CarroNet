<?php

require_once WS_PATH . 'xml_handler.php';

Final Class XML_Handler_Rdcars extends XML_Handler {


	public function __construct($received_data, $request_data) {

		$this->_haw_received_data = $received_data;
		$this->_haw_request_data = $request_data;

		/**
		 * Troca padrão de codificação SOAP para padrão de codificação XML aceitável em simplexml_load_string();
		 */
		$data = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>", $this->_haw_received_data);
		$data = preg_replace('/<\?xml.*?\?>/', "", $data); //<?php
		$received = simplexml_load_string($data);

		$data2 = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>", $this->_haw_request_data);
		$data2 = preg_replace('/<\?xml.*?\?>/', "", $data2); //<?php
        $requested = simplexml_load_string($data2);

		//var_dump($requested);
		if(isset($received->Body->RentalSearchEquipTypesResponse->RentalSearchEquipTypesResult->OTA_VehAvailRateRS)) {
			$this->_type = 'pesquisar';
			$this->parsePesquisar($requested, $received);
		} else if(isset($received->Body->Reserve2Response->Reserve2Result->OTA_VehResRS)) {
			$this->_type = 'reservar';
			$this->parseReservar($requested, $received);
		}else if (isset($received->Body->CancelReserveResponse ->CancelReserveResult->OTA_VehCancelRS)) {
			$this->_type = 'cancelar';
			$this->parseCancelar($received);
		}  else {
			$this->_is_ok = 2;
		}
	}

	private function parsePesquisar($requested, $received) {
		$this->_is_ok = 0;

		if(isset($received->Body->RentalSearchEquipTypesResponse->RentalSearchEquipTypesResult->OTA_VehAvailRateRS->Success)) {
			$p = (isset($received->Body->RentalSearchEquipTypesResponse->RentalSearchEquipTypesResult->OTA_VehAvailRateRS->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail))?$received->Body->RentalSearchEquipTypesResponse->RentalSearchEquipTypesResult->OTA_VehAvailRateRS->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail:NULL;
			if(isset($p->VehAvailCore["Status"]) && $p->VehAvailCore["Status"] == "Available") {
				$this->_is_ok = 1;
				$core = $received->Body->RentalSearchEquipTypesResponse->RentalSearchEquipTypesResult->OTA_VehAvailRateRS->VehAvailRSCore;
				$rentalcore = get_object_vars($core->VehRentalCore);
				$this->_arr_dados['ReturnDateTime'] = $rentalcore['@attributes']['ReturnDateTime'];
				$this->_arr_dados['PickUpDateTime'] = $rentalcore['@attributes']['PickUpDateTime'];
				$pickuploc = get_object_vars($rentalcore['PickUpLocation']);
				$this->_arr_dados['PickUpLocation'] = $pickuploc['@attributes']['LocationCode'];
				$returnloc = get_object_vars($rentalcore['ReturnLocation']);
				$this->_arr_dados['ReturnLocation'] = $returnloc['@attributes']['LocationCode'];
				$vendoravails = NULL;
				foreach($core->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail as $vendav) {
					$vendavail = get_object_vars($vendav);
					$vehicleavail = get_object_vars($vendavail['VehAvailCore']);
					$vehicleavail_info = get_object_vars($vendavail['VehAvailInfo']);
					$coverage_info = $vehicleavail_info['PricedCoverages'];
                    $opcionais_info = $vehicleavail['PricedEquips'];
					$vehicle = get_object_vars($vehicleavail['Vehicle']);
					$vehicle_type = get_object_vars($vehicle['VehType']);
					$vehicle_classe = get_object_vars($vehicle['VehClass']);
					$vehicle_modelo = get_object_vars($vehicle['VehMakeModel']);
					$rentalrate = get_object_vars($vehicleavail['RentalRate']);
					$rentalrate_distance = get_object_vars($rentalrate['RateDistance']);
					$rentalrate_charges = $rentalrate['VehicleCharges'];
					//$rentalrate_rate = get_object_vars($rentalrate['RateQualifier']);
					$totalrate = get_object_vars($vehicleavail['TotalCharge']);
					$taxas_arr = NULL;
					$rates_arr = NULL;
					foreach($rentalrate_charges->VehicleCharge as $charge) {
						$charge_unit = get_object_vars($charge);
						if((int)$charge_unit['@attributes']['Purpose'] == 1 || ((int)$charge_unit['@attributes']['Purpose'] == 11)) {

                           foreach($charge as $tarifa){
                               $rentalrate_calculation = get_object_vars($tarifa);
                               if(isset($rentalrate_calculation['@attributes']['UnitName']) && $rentalrate_calculation['@attributes']['UnitName'] == 'Day'){
                                   $rates_arr[$charge_unit['@attributes']['Purpose']] = array(
                                       'VehicleCharge_IncludedInRate'        => (isset($charge_unit['@attributes']['IncludedInRate']))?$charge_unit['@attributes']['IncludedInRate']:NULL,
                                       'VehicleCharge_RateConvertInd'        => (isset($charge_unit['@attributes']['RateConvertInd']))?$charge_unit['@attributes']['RateConvertInd']:NULL,
                                       'VehicleCharge_IncludedInEstTotalInd' => (isset($charge_unit['@attributes']['IncludedInEstTotalInd']))?$charge_unit['@attributes']['IncludedInEstTotalInd']:NULL,
                                       'VehicleCharge_Description'           => (isset($charge_unit['@attributes']['Description']))?$charge_unit['@attributes']['Description']:NULL,
                                       'VehicleCharge_DecimalPlaces'         => (isset($charge_unit['@attributes']['DecimalPlaces']))?$charge_unit['@attributes']['DecimalPlaces']:NULL,
                                       'VehicleCharge_CurrencyCode'          => (isset($charge_unit['@attributes']['CurrencyCode']))?$charge_unit['@attributes']['CurrencyCode']:NULL,
                                       'VehicleCharge_TaxInclusive'          => (isset($charge_unit['@attributes']['TaxInclusive']))?$charge_unit['@attributes']['TaxInclusive']:NULL,
                                       'VehicleCharge_Amount'                => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL,
                                       'VehicleCharge_Purpose'               => (isset($charge_unit['@attributes']['Purpose']))?$charge_unit['@attributes']['Purpose']:NULL,
                                       'Calculation_UnitCharge'              => (isset($rentalrate_calculation['@attributes']['UnitCharge']))?$rentalrate_calculation['@attributes']['UnitCharge']:NULL,
                                       'Calculation_UnitName'                => (isset($rentalrate_calculation['@attributes']['UnitName']))?$rentalrate_calculation['@attributes']['UnitName']:NULL,
                                       'Calculation_Quantity'                => (isset($rentalrate_calculation['@attributes']['Quantity']))?$rentalrate_calculation['@attributes']['Quantity']:NULL,
                                       'Calculation_Total'                   => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL
                                   );
                               }elseif(isset($rentalrate_calculation['@attributes']['UnitName']) && $rentalrate_calculation['@attributes']['UnitName'] == 'Hour'){
                                   $rates_arr[11] = array(
                                       'VehicleCharge_IncludedInRate'        => (isset($charge_unit['@attributes']['IncludedInRate']))?$charge_unit['@attributes']['IncludedInRate']:NULL,
                                       'VehicleCharge_RateConvertInd'        => (isset($charge_unit['@attributes']['RateConvertInd']))?$charge_unit['@attributes']['RateConvertInd']:NULL,
                                       'VehicleCharge_IncludedInEstTotalInd' => (isset($charge_unit['@attributes']['IncludedInEstTotalInd']))?$charge_unit['@attributes']['IncludedInEstTotalInd']:NULL,
                                       'VehicleCharge_Description'           => (isset($charge_unit['@attributes']['Description']))?$charge_unit['@attributes']['Description']:NULL,
                                       'VehicleCharge_DecimalPlaces'         => (isset($charge_unit['@attributes']['DecimalPlaces']))?$charge_unit['@attributes']['DecimalPlaces']:NULL,
                                       'VehicleCharge_CurrencyCode'          => (isset($charge_unit['@attributes']['CurrencyCode']))?$charge_unit['@attributes']['CurrencyCode']:NULL,
                                       'VehicleCharge_TaxInclusive'          => (isset($charge_unit['@attributes']['TaxInclusive']))?$charge_unit['@attributes']['TaxInclusive']:NULL,
                                       'VehicleCharge_Amount'                => (((isset($rentalrate_calculation['@attributes']['UnitCharge']))?$rentalrate_calculation['@attributes']['UnitCharge']:NULL) * ((isset($rentalrate_calculation['@attributes']['Quantity']))?$rentalrate_calculation['@attributes']['Quantity']:NULL)),
                                       'VehicleCharge_Purpose'               => 11,
                                       'Calculation_UnitCharge'              => (isset($rentalrate_calculation['@attributes']['UnitCharge']))?$rentalrate_calculation['@attributes']['UnitCharge']:NULL,
                                       'Calculation_UnitName'                => (isset($rentalrate_calculation['@attributes']['UnitName']))?$rentalrate_calculation['@attributes']['UnitName']:NULL,
                                       'Calculation_Quantity'                => (isset($rentalrate_calculation['@attributes']['Quantity']))?$rentalrate_calculation['@attributes']['Quantity']:NULL,
                                       'Calculation_Total'                   => (((isset($rentalrate_calculation['@attributes']['UnitCharge']))?$rentalrate_calculation['@attributes']['UnitCharge']:NULL) * ((isset($rentalrate_calculation['@attributes']['Quantity']))?$rentalrate_calculation['@attributes']['Quantity']:NULL)),
                                   );
                               }
                           }

							if($charge->TaxAmounts->TaxAmount){
                                $taxas_dados = get_object_vars($charge->TaxAmounts->TaxAmount);
                                $taxas_arr[2] = array(
                                    'IncludedInRate'        => true,
                                    'RateConvertInd'        => NULL,
                                    'IncludedInEstTotalInd' => NULL,
                                    'Description'           => (isset($taxas_dados['@attributes']['Description']))?$taxas_dados['@attributes']['Description']:NULL,
                                    'DecimalPlaces'         => NULL,
                                    'CurrencyCode'          => NULL,
                                    'TaxInclusive'          => NULL,
                                    'Amount'                => (isset($taxas_dados['@attributes']['Total']))?$taxas_dados['@attributes']['Total']:NULL,
                                    'Purpose'               => 2,
                                    'Percentage'            => (isset($taxas_dados['@attributes']['Percentage']))?$taxas_dados['@attributes']['Percentage']:NULL,
                                    'Quantity'              => NULL,
                                    'Total'                 => (isset($taxas_dados['@attributes']['Total']))?$taxas_dados['@attributes']['Total']:NULL
                                );
                            }else{
                                $taxas_dados = NULL;
                            }


						} else {
							$rentalrate_calculation = get_object_vars($charge->Calculation);
							if($charge_unit['@attributes']['Purpose'] == 11 && $charge_unit['@attributes']['Description'] != 'HORAS EXTRAS') {
								$code_tax = 111;
								$rates_arr[$code_tax] = array(
									'VehicleCharge_IncludedInRate'        => (isset($charge_unit['@attributes']['IncludedInRate']))?$charge_unit['@attributes']['IncludedInRate']:NULL,
									'VehicleCharge_RateConvertInd'        => (isset($charge_unit['@attributes']['RateConvertInd']))?$charge_unit['@attributes']['RateConvertInd']:NULL,
									'VehicleCharge_IncludedInEstTotalInd' => (isset($charge_unit['@attributes']['IncludedInEstTotalInd']))?$charge_unit['@attributes']['IncludedInEstTotalInd']:NULL,
									'VehicleCharge_Description'           => (isset($charge_unit['@attributes']['Description']))?$charge_unit['@attributes']['Description']:NULL,
									'VehicleCharge_DecimalPlaces'         => (isset($charge_unit['@attributes']['DecimalPlaces']))?$charge_unit['@attributes']['DecimalPlaces']:NULL,
									'VehicleCharge_CurrencyCode'          => (isset($charge_unit['@attributes']['CurrencyCode']))?$charge_unit['@attributes']['CurrencyCode']:NULL,
									'VehicleCharge_TaxInclusive'          => (isset($charge_unit['@attributes']['TaxInclusive']))?$charge_unit['@attributes']['TaxInclusive']:NULL,
									'VehicleCharge_Amount'                => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL,
									'VehicleCharge_Purpose'               => (isset($charge_unit['@attributes']['Purpose']))?$charge_unit['@attributes']['Purpose']:NULL,
									'Calculation_UnitCharge'              => (isset($rentalrate_calculation['@attributes']['UnitCharge']))?$rentalrate_calculation['@attributes']['UnitCharge']:NULL,
									'Calculation_UnitName'                => (isset($rentalrate_calculation['@attributes']['UnitName']))?$rentalrate_calculation['@attributes']['UnitName']:NULL,
									'Calculation_Quantity'                => (isset($rentalrate_calculation['@attributes']['Quantity']))?$rentalrate_calculation['@attributes']['Quantity']:NULL,
									'Calculation_Total'                   => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL
								);
							} else if($charge_unit['@attributes']['Purpose'] == 17) {
                                $taxas_arr[$charge_unit['@attributes']['Purpose']] = array(
                                    'IncludedInRate'        => (isset($charge_unit['@attributes']['IncludedInRate']))?$charge_unit['@attributes']['IncludedInRate']:NULL,
                                    'RateConvertInd'        => (isset($charge_unit['@attributes']['RateConvertInd']))?$charge_unit['@attributes']['RateConvertInd']:NULL,
                                    'IncludedInEstTotalInd' => (isset($charge_unit['@attributes']['IncludedInEstTotalInd']))?$charge_unit['@attributes']['IncludedInEstTotalInd']:NULL,
                                    'Description'           => 'Taxa de Retorno',
                                    'DecimalPlaces'         => (isset($charge_unit['@attributes']['DecimalPlaces']))?$charge_unit['@attributes']['DecimalPlaces']:NULL,
                                    'CurrencyCode'          => (isset($charge_unit['@attributes']['CurrencyCode']))?$charge_unit['@attributes']['CurrencyCode']:NULL,
                                    'TaxInclusive'          => (isset($charge_unit['@attributes']['TaxInclusive']))?$charge_unit['@attributes']['TaxInclusive']:NULL,
                                    'Amount'                => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL,
                                    'Purpose'               => (isset($charge_unit['@attributes']['Purpose']))?$charge_unit['@attributes']['Purpose']:NULL,
                                    'Percentage'            => NULL,
                                    'Quantity'              => (isset($rentalrate_calculation['@attributes']['Quantity']))?$rentalrate_calculation['@attributes']['Quantity']:NULL,
                                    'Total'                 => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL
                                );
                            } else {
								$taxas_arr[$charge_unit['@attributes']['Purpose']] = array(
									'IncludedInRate'        => (isset($charge_unit['@attributes']['IncludedInRate']))?$charge_unit['@attributes']['IncludedInRate']:NULL,
									'RateConvertInd'        => (isset($charge_unit['@attributes']['RateConvertInd']))?$charge_unit['@attributes']['RateConvertInd']:NULL,
									'IncludedInEstTotalInd' => (isset($charge_unit['@attributes']['IncludedInEstTotalInd']))?$charge_unit['@attributes']['IncludedInEstTotalInd']:NULL,
									'Description'           => (isset($charge_unit['@attributes']['Description']))?$charge_unit['@attributes']['Description']:NULL,
									'DecimalPlaces'         => (isset($charge_unit['@attributes']['DecimalPlaces']))?$charge_unit['@attributes']['DecimalPlaces']:NULL,
									'CurrencyCode'          => (isset($charge_unit['@attributes']['CurrencyCode']))?$charge_unit['@attributes']['CurrencyCode']:NULL,
									'TaxInclusive'          => (isset($charge_unit['@attributes']['TaxInclusive']))?$charge_unit['@attributes']['TaxInclusive']:NULL,
									'Amount'                => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL,
									'Purpose'               => (isset($charge_unit['@attributes']['Purpose']))?$charge_unit['@attributes']['Purpose']:NULL,
									'Percentage'            => NULL,
									'Quantity'              => (isset($rentalrate_calculation['@attributes']['Quantity']))?$rentalrate_calculation['@attributes']['Quantity']:NULL,
									'Total'                 => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL
								);
							}
						}
					}
					$coverage_arr = NULL;
					//if(is_array($coverage_info->PricedCoverage)){
					foreach($coverage_info->PricedCoverage as $cover) {

						$cover_unit = get_object_vars($cover);
						$coverage = get_object_vars($cover_unit['Coverage']);
						$coverage_charge = get_object_vars($cover_unit['Charge']);
						$coverage_calculation = get_object_vars($coverage_charge['Calculation']);
						$coverage_arr[$coverage['@attributes']['CoverageType']] = array(
							'CoverageType' => (isset($coverage['@attributes']['CoverageType']))?$coverage['@attributes']['CoverageType']:NULL,
							'Details'      => (isset($coverage['Details']))?$coverage['Details']:NULL,
							'UnitCharge'   => (isset($coverage_calculation['@attributes']['UnitCharge']))?$coverage_calculation['@attributes']['UnitCharge']:NULL,
							'Quantity'     => (isset($coverage_calculation['@attributes']['Quantity']))?$coverage_calculation['@attributes']['Quantity']:NULL,
							'Total'        => ((int)$coverage_calculation['@attributes']['UnitCharge'] * (int)$coverage_calculation['@attributes']['Quantity']),
							'Included'     => (isset($coverage_charge['@attributes']['IncludedInRate']) && $coverage_charge['@attributes']['IncludedInRate'] == 'true') ? TRUE : FALSE
						);

					}
					//}
                    $opcionais_arr = NULL;
                    foreach($opcionais_info->PricedEquip as $opp){

                        $opp_unit = get_object_vars($opp);
                        $opcional = get_object_vars($opp_unit['Equipment']);
                        $opcional_charge = get_object_vars($opp_unit['Charge']);
                        $opcional_calculation = get_object_vars($opcional_charge['Calculation']);
                        $opcionais_arr[$opcional['@attributes']['EquipType']][] = array(
                            'EquipType' => (isset($opcional['@attributes']['EquipType']))?$opcional['@attributes']['EquipType']:NULL,
                            'Description'      => (isset($opcional['Description']))?$opcional['Description']:NULL,
                            'UnitCharge'   => (isset($opcional_calculation['@attributes']['UnitCharge']))?$opcional_calculation['@attributes']['UnitCharge']:NULL,
                            'UnitName'   => (isset($opcional_calculation['@attributes']['UnitName']))?$opcional_calculation['@attributes']['UnitName']:NULL,
                            'Quantity'     => (isset($opcional_calculation['@attributes']['Quantity']))?$opcional_calculation['@attributes']['Quantity']:NULL,
                            'Total'        => ((int)$opcional_calculation['@attributes']['UnitCharge'] * (int)$opcional_calculation['@attributes']['Quantity']),
                            'Included'	 => (isset($opcional['@attributes']['Required']) && $opcional['@attributes']['Required'] == 'true')?TRUE:FALSE
                        );

                    }

					$code_xml = strtoupper($vehicle['@attributes']['Code']);
					$rate_code = ($rentalrate_distance['@attributes']['Unlimited'] == 'true')?'kmfree':'kmlimit';
					$vendoravails[$code_xml][$rate_code] = (array(
                        'CodeXml' => $code_xml,
                        'PickUpLocation' => $pickuploc['@attributes']['LocationCode'],
                        'ReturnLocation' => $returnloc['@attributes']['LocationCode'],
						'RatePeriod'                         => (isset($vendavail['@attributes']['RatePeriod']))?$vendavail['@attributes']['RatePeriod']:NULL,
						'RateCategory'                       => (isset($vendavail['@attributes']['RateCategory']))?$vendavail['@attributes']['RateCategory']:NULL,
						'AirConditionInd'                    => (isset($vehicle['@attributes']['AirConditionInd']))?$vehicle['@attributes']['AirConditionInd']:NULL,
						'TransmissionType'                   => (isset($vehicle['@attributes']['TransmissionType']))?$vehicle['@attributes']['TransmissionType']:NULL,
						'Description'                        => (isset($vehicle['@attributes']['Description']))?$vehicle['@attributes']['Description']:NULL,
						'BaggageQuantity'                    => (isset($vehicle['@attributes']['BaggageQuantity']))?$vehicle['@attributes']['BaggageQuantity']:NULL,
						'PassengerQuantity'                  => (isset($vehicle['@attributes']['PassengerQuantity']))?$vehicle['@attributes']['PassengerQuantity']:NULL,
						'Code'                               => (isset($vehicle['@attributes']['Code']))?$vehicle['@attributes']['Code']:NULL,
						'VehicleCategory'                    => (isset($vehicle_type['@attributes']['VehicleCategory']))?$vehicle_type['@attributes']['VehicleCategory']:NULL,
						'DoorCount'                          => (isset($vehicle_type['@attributes']['DoorCount']))?$vehicle_type['@attributes']['DoorCount']:NULL,
						'Size'                               => (isset($vehicle_classe['@attributes']['Size']))?$vehicle_classe['@attributes']['Size']:NULL,
						'Modelo'                             => (isset($vehicle_modelo['@attributes']['Name']))?$vehicle_modelo['@attributes']['Name']:NULL,
						'RateDistance_Unlimited'             => (isset($rentalrate_distance['@attributes']['Unlimited']))?$rentalrate_distance['@attributes']['Unlimited']:NULL,
						'RateDistance_DistUnitName'          => (isset($rentalrate_distance['@attributes']['DistUnitName']))?$rentalrate_distance['@attributes']['DistUnitName']:NULL,
						'RateDistance_VehiclePeriodUnitName' => (isset($rentalrate_distance['@attributes']['VehiclePeriodUnitName']))?$rentalrate_distance['@attributes']['VehiclePeriodUnitName']:NULL,
						'RateDistance_Quantity'              => (isset($rentalrate_distance['@attributes']['Quantity']))?$rentalrate_distance['@attributes']['Quantity']:NULL,
						'VehicleCharges'                     => $rates_arr,
						//'Rate_RatePeriod'] => $rentalrate_rate['@attributes']['RatePeriod'],
						//'Rate_RateQualifier'] => $rentalrate_rate['@attributes']['RateQualifier'],
						//'Rate_RateCategory'] => $rentalrate_rate['@attributes']['RateCategory'],
						'TotalCharge_RateTotalAmount'        => (isset($totalrate['@attributes']['RateTotalAmount']))?$totalrate['@attributes']['RateTotalAmount']:NULL,
						'TotalCharge_EstimatedTotalAmount'   => (isset($totalrate['@attributes']['EstimatedTotalAmount']))?$totalrate['@attributes']['EstimatedTotalAmount']:NULL,
						'TotalCharge_CurrencyCode'           => (isset($totalrate['@attributes']['CurrencyCode']))?$totalrate['@attributes']['CurrencyCode']:NULL,
						'TotalCharge_DecimalPlaces'          => (isset($totalrate['@attributes']['DecimalPlaces']))?$totalrate['@attributes']['DecimalPlaces']:NULL,
						'TotalCharge_RateConvertInd'         => (isset($totalrate['@attributes']['RateConvertInd']))?$totalrate['@attributes']['RateConvertInd']:NULL,
						'Fees'                               => $taxas_arr,
						'Coverage'                           => $coverage_arr,
                        'Equip'                              => $opcionais_arr
					));
				}
				$this->_arr_dados['vendoravail'] = $vendoravails;
			}
		}
	}

	private function parseReservar($request, $received) {
		if($received->Body->Reserve2Response->Reserve2Result->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"] != "") {

			$this->_is_ok = 1;
			$this->_xml_id = $received->Body->Reserve2Response->Reserve2Result->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];

			$this->_diaria_sem_taxa = $received->Body->Reserve2Response->Reserve2Result->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->Calculation["UnitCharge"];
			$this->_protecao = $received->Body->Reserve2Response->Reserve2Result->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->PricedCoverages->PricedCoverage->Charge['Amount'];

			$o = $received->Body->Reserve2Response->Reserve2Result->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->TaxAmounts;
			for($i = 0, $s = count($o->TaxAmount); $i < $s; $i++) {
				$total_taxas = $total_taxas + floatval($o->TaxAmount[$i]["Total"]);
			}
			$this->_taxas_diaria = $total_taxas;
			$this->_valor_total = floatval($received->Body->Reserve2Response->Reserve2Result->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["EstimatedTotalAmount"]);
		} else {
			$this->_is_ok = 0;
		}
	}
	private function parseCancelar ($received) {
		$this->_is_ok = 0;
		$this->_vis_errorTrack = 'Teste';
		if (isset($received->Body->CancelReserveResponse->CancelReserveResult->OTA_VehCancelRS->Success)) 	{
			$this->_is_ok = 1;
			$this->_vis_errorTrack = "Reserva cancelada com sucesso";
		}else{
			$this->parseError($received);
		}
	}
	private function parseError ($received) {
		$this->_is_ok = 0;
		$this->_vis_errorTrack = $received;
	}
}

?>