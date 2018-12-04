<?php
    require_once WS_PATH . 'xml_handler.php';

    Final Class XML_Handler_Fleetmax extends XML_Handler
    {
        //protected $_a = 'teste';
        public function __construct($received_data, $request_data)
        {
            $this->_haw_received_data = $received_data;
            $this->_haw_request_data = $request_data;
            /**
             * Troca padrão de codificação SOAP para padrão de codificação XML aceitável em simplexml_load_string();
             */
            $data = preg_replace('/<(\/|)SOAP-ENV:(Envelope|Body|Fault).*?>/', "<$1$2>",$this->_haw_received_data); //<?php
            $data = preg_replace('/<(\/|)NS1:(.*?)>/', "<$1$2>",$data); //<?php
            $data = preg_replace('/<(\/|)ns:(.*?)>/', "<$1$2>",$data); //<?php

            $data2 = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>", $this->_haw_request_data);
            $data2 = preg_replace('/<\?xml.*?\?>/', "", $data2);
            $request = simplexml_load_string($data2);
            $patterns = array();
            $patterns[0] = '/xsi:/';
            $patterns[1] = '/xsi:/';
            $replacements = array();
            $replacements[2] = '';
            $replacements[1] = '';
            $data = preg_replace($patterns, $replacements, $data);
            $recebido = simplexml_load_string($data);
            $received = $recebido->Body;
            if ($received !== null) {
                if (isset($received->ConsultaDisponibilidadeResponse->return->Envelope->Body->Response->OTA_VehAvailRateRS)) {
                    $this->_type = 'pesquisar';
                    $this->parsePesquisar($received->ConsultaDisponibilidadeResponse->return->Envelope->Body->Response->OTA_VehAvailRateRS);
                } else {
                    if (isset($received->ConfirmaReservaResponse->return->OTA_VehResRS)) {
                        $this->_type = 'reservar';
                        $this->parseReservar($request, $received->ConfirmaReservaResponse->return->OTA_VehResRS);
                    } else {
                        if ($received->ConsultaReservaResponse->return->OTA_VehRetResRS) {
                            $this->_type = 'visualizar';
                            $this->parseVisualizar($received->ConsultaReservaResponse->return->OTA_VehRetResRS);
                        } else {
                            if ($received->CancelReservaResponse->return->OTA_VehCancelRS) {
                                $this->_type = 'cancelar';
                                $this->parseCancelar($received->CancelReservaResponse->return->OTA_VehCancelRS);
                            } else {
                                if ($received->OTA_PayloadStdAttributes["Status"] == "0") {
                                    $this->parseError($received);
                                } else {
                                    $this->_is_ok = 0;
                                }
                            }
                        }
                    }
                }
            } else {
                $this->_is_ok = 2;
            }
        }

        private function parsePesquisar($received)
        {

            if (isset($received->Success)) {
                $status = get_object_vars($received->VehAvailRsCore->VehVendedorAvails->VehVendedorAvail->VehAvails->VehAvail->VehAvailCore);
                if ($status["@attributes"]["Status"] == "Available") {
                    $this->_is_ok = 1;
                    $core = $received->VehAvailRsCore;
                    $rentalcore = get_object_vars($core->VehRentalCore);
                    $this->_arr_dados['ReturnDateTime'] = $rentalcore['@attributes']['ReturnDateTime'];
                    $this->_arr_dados['PickUpDateTime'] = $rentalcore['@attributes']['PickUpDateTime'];
                    $pickuploc = get_object_vars($rentalcore['PickUpLocation']);
                    $this->_arr_dados['PickUpLocation'] = $pickuploc['@attributes']['LocationCode'];
                    $returnloc = get_object_vars($rentalcore['ReturnLocation']);
                    $this->_arr_dados['ReturnLocation'] = $returnloc['@attributes']['LocationCode'];
                    $vendoravails = null;
                    foreach ($core->VehVendedorAvails->VehVendedorAvail->VehAvails->VehAvail as $vendav) {
                        $vendavail = get_object_vars($vendav);
                        $vehicleavail = get_object_vars($vendavail['VehAvailCore']);
                        $vehicleavail_info = get_object_vars($vendavail['VehAvailInfo']);
                        $coverage_info = $vehicleavail_info['PricedCoverages'];
                        $opcionais_info = $vehicleavail['PricedEquips'];
                        $vehicle = get_object_vars($vehicleavail['Vehicle']);
                        $vehicle_type = get_object_vars($vehicle['VehType'][0]);
                        $vehicle_classe = get_object_vars($vehicle['VehClass']);
                        $vehicle_modelo = get_object_vars($vehicle['VehType'][1]);
                        $rentalrate = get_object_vars($vehicleavail['RentalRate']);
                        $rentalrate_distance = get_object_vars($rentalrate['RateDistance']);
                        $rentalrate_charges = $rentalrate['VehicleCharges'];
                        $rentalrate_rate = get_object_vars($rentalrate['RateQualifier']);
                        $totalrate = get_object_vars($vehicleavail['TotalCharge']);
                        $taxas_arr = null;
                        $rates_arr = null;
                        foreach ($rentalrate_charges->VehicleCharge as $charge) {
                            $charge_unit = get_object_vars($charge);
                            if ((int)$charge_unit['@attributes']['Purpose'] == 1 || ((int)$charge_unit['@attributes']['Purpose'] == 11 && $charge_unit['@attributes']['Description'] == 'HORAS EXTRAS')) {
                                $rentalrate_calculation = get_object_vars($charge->Calculation);
                                $tax_calculation = get_object_vars($charge->TaxAmounts->TaxAmount);
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
                                $taxas_arr[13] = array(
                                    'IncludedInRate'        => TRUE,
                                    'RateConvertInd'        => $tax_calculation['@attributes']['RateConvertInd'],
                                    'IncludedInEstTotalInd' => $tax_calculation['@attributes']['IncludedInEstTotalInd'],
                                    'Description'           => $tax_calculation['@attributes']['Description'],
                                    'DecimalPlaces'         => $tax_calculation['@attributes']['DecimalPlaces'],
                                    'CurrencyCode'          => $tax_calculation['@attributes']['CurrencyCode'],
                                    'TaxInclusive'          => $tax_calculation['@attributes']['TaxInclusive'],
                                    'Amount'                => $tax_calculation['@attributes']['Total'],
                                    'Purpose'               => 13,
                                    'Percentage'            => $tax_calculation['@attributes']['Percentage'],
                                    'Quantity'              => NULL,
                                    'Total'                 => $tax_calculation['@attributes']['Total']
                                );
                            } else {
                                $rentalrate_calculation = get_object_vars($charge->Calculation);
                                if ($charge_unit['@attributes']['Purpose'] == 11 && $charge_unit['@attributes']['Description'] != 'HORAS EXTRAS') {
                                    $code_tax = 111;
                                    $rates_arr[$code_tax] = array(
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
                                    $taxas_arr[$charge_unit['@attributes']['Purpose']] = array(
                                        'IncludedInRate'        => $charge_unit['@attributes']['IncludedInRate'],
                                        'RateConvertInd'        => $charge_unit['@attributes']['RateConvertInd'],
                                        'IncludedInEstTotalInd' => $charge_unit['@attributes']['IncludedInEstTotalInd'],
                                        'Description'           => $charge_unit['@attributes']['Description'],
                                        'DecimalPlaces'         => $charge_unit['@attributes']['DecimalPlaces'],
                                        'CurrencyCode'          => $charge_unit['@attributes']['CurrencyCode'],
                                        'TaxInclusive'          => $charge_unit['@attributes']['TaxInclusive'],
                                        'Amount'                => $charge_unit['@attributes']['Amount'],
                                        'Purpose'               => $charge_unit['@attributes']['Purpose'],
                                        'Percentage'            => null,
                                        'Quantity'              => $rentalrate_calculation['@attributes']['Quantity'],
                                        'Total'                 => $charge_unit['@attributes']['Amount']
                                    );
                                }
                            }
                        }
                        $coverage_arr = null;
                        //if(is_array($coverage_info->PricedCoverage)){
                        foreach ($coverage_info->PricedCoverage as $cover) {
                            $cover_unit = get_object_vars($cover);
                            $coverage = get_object_vars($cover_unit['Coverage']);
                            $coverage_charge = get_object_vars($cover_unit['Charge']);
                            $coverage_calculation = get_object_vars($coverage_charge['Calculation']);
                            $coverage_arr[$coverage['@attributes']['CoverageType']] = array(
                                'CoverageType' => $coverage['@attributes']['CoverageType'],
                                'Details'      => $coverage['Details'],
                                'UnitCharge'   => $coverage_calculation['@attributes']['UnitCharge'],
                                'Quantity'     => $coverage_calculation['@attributes']['Quantity'],
                                'Total'        => ((int)$coverage_calculation['@attributes']['UnitCharge'] * (int)$coverage_calculation['@attributes']['Quantity']),
                                'Included'     => ($coverage_charge['@attributes']['IncludedInRate'] == 'true') ? true : false
                            );

                        }
                        //}
                        $opcionais_arr = null;
                        foreach ($opcionais_info->PricedEquip as $opp) {
                            $opp_unit = get_object_vars($opp);
                            $opcional = get_object_vars($opp_unit['Equipment']);
                            $opcional_charge = get_object_vars($opp_unit['Charge']);
                            $opcional_calculation = get_object_vars($opcional_charge['Calculation']);
                            $opcionais_arr[$opcional['@attributes']['EquipType']][] = array(
                                'EquipType'   => $opcional['@attributes']['EquipType'],
                                'Description' => $opcional['Description'],
                                'UnitCharge'  => $opcional_calculation['@attributes']['UnitCharge'],
                                'UnitName'    => $opcional_calculation['@attributes']['UnitName'],
                                'Quantity'    => $opcional_calculation['@attributes']['Quantity'],
                                'Total'       => ((int)$opcional_calculation['@attributes']['UnitCharge'] * (int)$opcional_calculation['@attributes']['Quantity']),
                                'Included'    => ($opcional_charge['@attributes']['IncludedInRate'] == 'true') ? true : false
                            );

                        }
                        $code_xml = strtoupper($vehicle['@attributes']['Code']); 
                        $vendoravails[$code_xml]['CodeXml'] = $code_xml;
                        $vendoravails[$code_xml]['PickUpLocation'] = $pickuploc['@attributes']['LocationCode'];
                        $vendoravails[$code_xml]['ReturnLocation'] = $returnloc['@attributes']['LocationCode'];
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
                        $vendoravails[$code_xml]['RateDistance_Quantity'] = $rentalrate_distance['@attributes']['Quantity'];
                        $vendoravails[$code_xml]['VehicleCharges'] = $rates_arr;
                        $vendoravails[$code_xml]['Rate_RatePeriod'] = $rentalrate_rate['@attributes']['RatePeriod'];
                        $vendoravails[$code_xml]['Rate_RateQualifier'] = $rentalrate_rate['@attributes']['RateQualifier'];
                        $vendoravails[$code_xml]['Rate_RateCategory'] = $rentalrate_rate['@attributes']['RateCategory'];
                        $vendoravails[$code_xml]['TotalCharge_RateTotalAmount'] = $totalrate['@attributes']['RateTotalAmount'];
                        $vendoravails[$code_xml]['TotalCharge_EstimatedTotalAmount'] = $totalrate['@attributes']['EstimatedTotalAmount'];
                        $vendoravails[$code_xml]['TotalCharge_CurrencyCode'] = $totalrate['@attributes']['CurrencyCode'];
                        $vendoravails[$code_xml]['TotalCharge_DecimalPlaces'] = $totalrate['@attributes']['DecimalPlaces'];
                        $vendoravails[$code_xml]['TotalCharge_RateConvertInd'] = $totalrate['@attributes']['RateConvertInd'];
                        $vendoravails[$code_xml]['Fees'] = $taxas_arr;
                        $vendoravails[$code_xml]['Coverage'] = $coverage_arr;
                        $vendoravails[$code_xml]['Equip'] = $opcionais_arr;
                    }
                    $this->_arr_dados['vendoravail'] = $vendoravails;
                }
            }
        }

        private function parseReservar($request, $received)
        {
            if (isset($received->Success)) {
                $this->_is_ok = 1;
                $this->_xml_id = $received->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
                $this->_diaria_sem_taxa = $received->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge["Amount"];
                $this->_taxas_diaria = floatval($received->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->TaxAmounts->TaxAmount[0]["Total"]);
                $this->_taxas_extra = floatval($received->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->TaxAmounts->TaxAmount[1]["Total"]);
                $this->_valor_total = floatval($received->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["EstimatedTotalAmount"]);
            } else {
                $this->_is_ok = 0;
            }
        }

        private function parseVisualizar($received)
        {
            $this->_is_ok = 0;
            if (isset($received->Success)) {
                $status = get_object_vars($received->VehRetResRSCore->VehReservation);
                $this->_is_ok = 1;
                $this->_xml_id = $received->VehRetResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
                $this->_status_reserva = $status["@attributes"]["ReservationStatus"];
                $this->_vis_name = $received->VehRetResRSCore->VehReservation->Customer->Primary->PersonName->GivenName;
                $this->_vis_surname = $received->VehRetResRSCore->VehReservation->Customer->Primary->PersonName->Surname;
                $this->_vis_dtareti = $received->VehRetResRSCore->VehReservation->VehSegmentCore->VehRentalCore["PickUpDateTime"];
                $this->_vis_dtadevo = $received->VehRetResRSCore->VehReservation->VehSegmentCore->VehRentalCore["ReturnDateTime"];
                $this->_vis_ljreti = $received->VehRetResRSCore->VehReservation->VehSegmentInfo->LocationDetails[0]["Name"];
                $this->_vis_ljdevo = $received->VehRetResRSCore->VehReservation->VehSegmentInfo->LocationDetails[1]["Name"];
                $this->_diaria_sem_taxa = floatval($received->VehRetResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge["Amount"]);
                $this->_taxas_diaria = $received->VehRetResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->TaxAmounts->TaxAmount["Total"];
                $this->_valor_total = $received->VehRetResRSCore->VehReservation->VehSegmentCore->TotalCharge["EstimatedTotalAmount"];
                $this->_nmb_diarias = $received->VehRetResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->Calculation["Quantity"];
                $this->_vis_ciaaerea = $received->VehRetResRSInfo->TPA_Extensions->ArrivalDetails->MarketingCompany["Code"];
                $this->_vis_nmbvoo = $received->VehRetResRSInfo->TPA_Extensions->ArrivalDetails["Number"];
            } else {
                $this->parseError($received);
            }
        }

        private function parseCancelar($received)
        {
            $this->_is_ok = 0;
            $this->_vis_errorTrack = 'Teste';
            if ($received->VehCancelRSCore["CancelStatus"] == "Cancelled") {
                $this->_is_ok = 1;
                $this->_vis_errorTrack = "Reserva cancelada com sucesso";
            } else {
                $this->parseError($received);
            }
        }

        private function parseError($received)
        {
            $this->_is_ok = 0;
            $this->_vis_errorTrack = $received->OTA_PayloadStdAttributes['ErrorMessage'];
        }
    }