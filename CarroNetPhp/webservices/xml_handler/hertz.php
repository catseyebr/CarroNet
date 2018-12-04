<?php
    require_once WS_PATH . 'xml_handler.php';
    
    Final Class XML_Handler_Hertz extends XML_Handler
    {
        //protected $_a = 'teste';
        public function __construct($received_data, $request_data)
        {
            $this->_haw_received_data = $received_data;
            $this->_haw_request_data = $request_data;
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
            $received = $receivedata;
            if (isset($received->VehAvailRSCore)) {
                $this->_type = 'pesquisar';
                $this->parsePesquisar($received);
            } else {
                if ($received->VehResRSCore) {
                    $this->_type = 'reservar';
                    $this->parseReservar($received);
                } else {
                    if ($received->VehRetResRSCore) {
                        $this->_type = 'visualizar';
                        $this->parseVisualizar($received);
                    } else {
                        if ($received->VehCancelRSCore) {
                            $this->_type = 'cancelar';
                            $this->parseCancelar($received);
                        } else {
                            if ($received->OTA_VehLocSearchRS) {
                                $this->_type = 'lojapesquisa';
                                $this->parseLojaPesquisa($received);
                            } else {
                                if ($received->OTA_VehLocDetailRS) {
                                    $this->_type = 'lojas';
                                    $this->parseLojas($received);
                                } else {
                                    if ($received->VehModifyRSCore) {
                                        $this->_type = 'alterar';
                                        $this->parseAlterar($received);
                                    } else {
                                        $this->_is_ok = 0;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        private function parsePesquisar($received)
        {
            $nmb_diarias = $this->_arr_dados['nmb_diarias'];
            
            $this->_is_ok = 0;
            if (isset($received->Success)) {
                if ($received->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail->VehAvailCore["Status"] == "Available" || $received->OTA_VehAvailRateResponse->OTA_VehAvailRateRS->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail->VehAvailCore["Status"] == "OnRequest") {
                    $core = $received->VehAvailRSCore;
                    $this->_is_ok = 1;
                    $rentalcore = get_object_vars($core->VehRentalCore);
                    $this->_arr_dados['ReturnDateTime'] = $rentalcore['@attributes']['ReturnDateTime'];
                    $this->_arr_dados['PickUpDateTime'] = $rentalcore['@attributes']['PickUpDateTime'];
                    $pickuploc = get_object_vars($rentalcore['PickUpLocation']);
                    $this->_arr_dados['PickUpLocation'] = $pickuploc['@attributes']['LocationCode'];
                    $returnloc = get_object_vars($rentalcore['ReturnLocation']);
                    $this->_arr_dados['ReturnLocation'] = $returnloc['@attributes']['LocationCode'];
                    $vendoravails = null;
                    foreach ($core->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail as $vendav) {
                        $vehicleavail = get_object_vars($vendav->VehAvailCore);
                        $coverage_info = $vendav->VehAvailInfo->PricedCoverages;
                        $vehicle = get_object_vars($vehicleavail['Vehicle']);
                        $vehicle_type = get_object_vars($vehicle['VehType']);
                        $vehicle_classe = get_object_vars($vehicle['VehClass']);
                        $vehicle_modelo = ($vehicle['VehMakeModel']) ? get_object_vars($vehicle['VehMakeModel']) : null;
                        $rentalrate = get_object_vars($vehicleavail['RentalRate']);
                        $rentalrate_distance = get_object_vars($rentalrate['RateDistance']);
                        $rentalrate_charges = $rentalrate['VehicleCharges'];
                        $rentalrate_rate = get_object_vars($rentalrate['RateQualifier']);
                        $totalrate = get_object_vars($vehicleavail['TotalCharge']);
                        $taxas = $vehicleavail['Fees'];
                        $taxas_arr = null;
                        foreach ($taxas->Fee as $txs) {
                            $taxas_unit = get_object_vars($txs);
                            $calc_c = get_object_vars($txs->Calculation);
                            $calc = ($calc_c) ? $calc_c : null;
                            $taxas_arr[$taxas_unit['@attributes']['Purpose']] = array(
                                'IncludedInRate'        => $taxas_unit['@attributes']['IncludedInRate'],
                                'RateConvertInd'        => $taxas_unit['@attributes']['RateConvertInd'],
                                'IncludedInEstTotalInd' => $taxas_unit['@attributes']['IncludedInEstTotalInd'],
                                'Description'           => $taxas_unit['@attributes']['Description'],
                                'DecimalPlaces'         => $taxas_unit['@attributes']['DecimalPlaces'],
                                'CurrencyCode'          => $taxas_unit['@attributes']['CurrencyCode'],
                                'TaxInclusive'          => $taxas_unit['@attributes']['TaxInclusive'],
                                'Amount'                => $taxas_unit['@attributes']['Amount'],
                                'Purpose'               => $taxas_unit['@attributes']['Purpose'],
                                'Percentage'            => ($calc) ? $calc['@attributes']['Percentage'] : null,
                                'Total'                 => ($calc) ? $calc['@attributes']['Total'] : null,
                            );
                        }
                        $rates_arr = null;
                        foreach ($rentalrate_charges->VehicleCharge as $charge) {
                            $charge_unit = get_object_vars($charge);
                            if (is_array($charge->Calculation)) {
                                foreach ($charge->Calculation as $tar_charge) {
                                    $rentalrate_calculation = get_object_vars($tar_charge);
                                    if ($rentalrate_calculation['@attributes']['UnitName'] == 'Day' || $rentalrate_calculation['@attributes']['UnitName'] == 'Week') {
                                        $rates_arr[$charge_unit['@attributes']['Purpose']] = array(
                                            'VehicleCharge_IncludedInRate'        => $charge_unit['@attributes']['IncludedInRate'],
                                            'VehicleCharge_RateConvertInd'        => $charge_unit['@attributes']['RateConvertInd'],
                                            'VehicleCharge_IncludedInEstTotalInd' => $charge_unit['@attributes']['IncludedInEstTotalInd'],
                                            'VehicleCharge_Description'           => $charge_unit['@attributes']['Description'],
                                            'VehicleCharge_DecimalPlaces'         => $charge_unit['@attributes']['DecimalPlaces'],
                                            'VehicleCharge_CurrencyCode'          => $charge_unit['@attributes']['CurrencyCode'],
                                            'VehicleCharge_TaxInclusive'          => $charge_unit['@attributes']['TaxInclusive'],
                                            'VehicleCharge_Amount'                => $charge_unit['@attributes']['Amount'],
                                            'VehicleCharge_Purpose'               => $charge_unit['@attributes']['Purpose'],
                                            'Calculation_UnitCharge'              => $rentalrate_calculation['@attributes']['UnitCharge'],
                                            'Calculation_UnitName'                => $rentalrate_calculation['@attributes']['UnitName'],
                                            'Calculation_Quantity'                => $rentalrate_calculation['@attributes']['Quantity'],
                                            'Calculation_Total'                   => $charge_unit['@attributes']['Amount']
                                        );
                                    } else {
                                        if ($rentalrate_calculation['@attributes']['UnitName'] == 'Hour') {
                                            $rates_arr[150] = array(
                                                'VehicleCharge_IncludedInRate'        => $charge_unit['@attributes']['IncludedInRate'],
                                                'VehicleCharge_RateConvertInd'        => $charge_unit['@attributes']['RateConvertInd'],
                                                'VehicleCharge_IncludedInEstTotalInd' => $charge_unit['@attributes']['IncludedInEstTotalInd'],
                                                'VehicleCharge_Description'           => $charge_unit['@attributes']['Description'],
                                                'VehicleCharge_DecimalPlaces'         => $charge_unit['@attributes']['DecimalPlaces'],
                                                'VehicleCharge_CurrencyCode'          => $charge_unit['@attributes']['CurrencyCode'],
                                                'VehicleCharge_TaxInclusive'          => $charge_unit['@attributes']['TaxInclusive'],
                                                'VehicleCharge_Amount'                => $charge_unit['@attributes']['Amount'],
                                                'VehicleCharge_Purpose'               => $charge_unit['@attributes']['Purpose'],
                                                'Calculation_UnitCharge'              => $rentalrate_calculation['@attributes']['UnitCharge'],
                                                'Calculation_UnitName'                => $rentalrate_calculation['@attributes']['UnitName'],
                                                'Calculation_Quantity'                => $rentalrate_calculation['@attributes']['Quantity'],
                                                'Calculation_Total'                   => $charge_unit['@attributes']['Amount']
                                            );
                                        }
                                    }
                                }
                            } else {
                                $rentalrate_calculation = get_object_vars($charge->Calculation);
                                $rates_arr[$charge_unit['@attributes']['Purpose']] = array(
                                    'VehicleCharge_IncludedInRate'        => $charge_unit['@attributes']['IncludedInRate'],
                                    'VehicleCharge_RateConvertInd'        => $charge_unit['@attributes']['RateConvertInd'],
                                    'VehicleCharge_IncludedInEstTotalInd' => $charge_unit['@attributes']['IncludedInEstTotalInd'],
                                    'VehicleCharge_Description'           => $charge_unit['@attributes']['Description'],
                                    'VehicleCharge_DecimalPlaces'         => $charge_unit['@attributes']['DecimalPlaces'],
                                    'VehicleCharge_CurrencyCode'          => $charge_unit['@attributes']['CurrencyCode'],
                                    'VehicleCharge_TaxInclusive'          => $charge_unit['@attributes']['TaxInclusive'],
                                    'VehicleCharge_Amount'                => $charge_unit['@attributes']['Amount'],
                                    'VehicleCharge_Purpose'               => $charge_unit['@attributes']['Purpose'],
                                    'Calculation_UnitCharge'              => $rentalrate_calculation['@attributes']['UnitCharge'],
                                    'Calculation_UnitName'                => $rentalrate_calculation['@attributes']['UnitName'],
                                    'Calculation_Quantity'                => $rentalrate_calculation['@attributes']['Quantity'],
                                    'Calculation_Total'                   => $charge_unit['@attributes']['Amount']
                                );
                            }
                        }
                        $coverage_arr = null;
                        if ($coverage_info) {
                            foreach ($coverage_info->PricedCoverage as $cover) {
                                $cover_unit = get_object_vars($cover);
                                $coverage = get_object_vars($cover_unit['Coverage']);
                                $coverage_charge = get_object_vars($cover_unit['Charge']);
                                if (is_array($coverage_charge['Calculation'])) {
                                    foreach ($coverage_charge['Calculation'] as $cover_arr) {
                                        $coverage_calculation = get_object_vars($cover_arr);
                                        $coverage_arr[$coverage['@attributes']['CoverageType']][$coverage_calculation['@attributes']['UnitName']] = array(
                                            'CoverageType' => $coverage['@attributes']['CoverageType'],
                                            'Details'      => $coverage_calculation['@attributes']['UnitName'],
                                            'UnitCharge'   => $coverage_calculation['@attributes']['UnitCharge'],
                                            'Total'        => $coverage_calculation['@attributes']['UnitCharge'],
                                        );
                                    }
                                } else {
                                    $coverage_calculation = get_object_vars($coverage_charge['Calculation']);
                                    $coverage_arr[$coverage['@attributes']['CoverageType']][$coverage_calculation['@attributes']['UnitName']] = array(
                                        'CoverageType' => $coverage['@attributes']['CoverageType'],
                                        'Details'      => $coverage_calculation['@attributes']['UnitName'],
                                        'UnitCharge'   => $coverage_calculation['@attributes']['UnitCharge'],
                                        'Total'        => $coverage_calculation['@attributes']['UnitCharge'],
                                    );
                                }
                            }
                        }
                        $code_xml = strtoupper($vehicle['@attributes']['Code']);
                        $references = get_object_vars($vehicleavail['Reference']);
                        $vendoravails[$code_xml]['RatePeriod'] = $vendav['@attributes']['RatePeriod'];
                        $vendoravails[$code_xml]['RateCategory'] = $vendav['@attributes']['RateCategory'];
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
                        $vendoravails[$code_xml]['VehicleCharges'] = $rates_arr;
                        $vendoravails[$code_xml]['Rate_RatePeriod'] = $rentalrate_rate['@attributes']['RatePeriod'];
                        $vendoravails[$code_xml]['Rate_RateQualifier'] = $rentalrate_rate['@attributes']['RateQualifier'];
                        $vendoravails[$code_xml]['Rate_RateCategory'] = $rentalrate_rate['@attributes']['RateCategory'];
                        $vendoravails[$code_xml]['MinimumDayInd'] = null;
                        $vendoravails[$code_xml]['MaximumDayInd'] = null;
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
                } else {
                    $this->_is_ok = 0;
                }
                
            }
        }
        
        private function parseReservar($received)
        {
            if (isset($received->Success)) {
                $this->_is_ok = 1;
                $this->_xml_id = $received->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
                $this->_diaria_sem_taxa = floatval($received->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["RateTotalAmount"]);
                $this->_valor_total = floatval($received->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["EstimatedTotalAmount"]);
                $this->_taxas_diaria = floatval($received->VehResRSCore->VehReservation->VehSegmentCore->Fees->Fee["Amount"]);
            }
        }
        
        private function parseVisualizar($received)
        {
            $this->_is_ok = 0;
            if (isset($received->Success)) {
                $this->_is_ok = 1;
                $this->_status_reserva = $received->VehRetResRSCore->VehReservation->VehSegmentCore->ConfID["Type"];
                $this->_xml_id = $received->VehRetResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
                if ($this->_xml_id == '') {
                    $this->_status_reserva = 'Cancelled';
                }
            } else {
                $this->parseError($received);
            }
        }
        
        private function parseCancelar($received)
        {
            $this->_is_ok = 0;
            $this->_vis_errorTrack = 'Teste';
            if (isset($received->Success)) {
                $this->_is_ok = 1;
                $this->_vis_errorTrack = "Reserva cancelada com sucesso";
            } else {
                $this->parseError($received);
            }
        }
        
        private function parseLojaPesquisa($received)
        {
            var_dump($received);
        }
        
        private function parseLojas($received)
        {
            var_dump($received);
        }
        
        private function parseError($received)
        {
            $this->_is_ok = 0;
            $this->_vis_errorTrack = $received;
        }
        
        private function parseAlterar($received)
        {
            if (isset($received->Success)) {
                $this->_is_ok = 1;
                $this->_xml_id = $received->VehModifyRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
                $this->_diaria_sem_taxa = floatval($received->VehModifyRSCore->VehReservation->VehSegmentCore->TotalCharge["RateTotalAmount"]);
                $this->_valor_total = floatval($received->VehModifyRSCore->VehReservation->VehSegmentCore->TotalCharge["EstimatedTotalAmount"]);
                $this->_taxas_diaria = floatval($received->VehModifyRSCore->VehReservation->VehSegmentCore->Fees->Fee["Amount"]);
            }
        }
    }

?>