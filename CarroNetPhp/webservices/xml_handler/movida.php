<?php
    require_once WS_PATH . 'xml_handler.php';

    Final Class XML_Handler_Movida extends XML_Handler
    {
        //protected $_a = 'teste';
        public function __construct($received_data, $request_data, $parser_data)
        {
            $this->_haw_received_data = $received_data;
            $this->_haw_request_data = $request_data;
            $this->_parser_data = $parser_data;
            /**
             * Troca padrão de codificação SOAP para padrão de codificação XML aceitável em simplexml_load_string();
             */
            $data = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>", $this->_haw_received_data);
            $data = preg_replace('/<\?xml.*?\?>/', "", $data); //<?php
            $received = simplexml_load_string($data);
            //$received = $received->Body;
            $data2 = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>", $this->_haw_request_data);
            $data2 = preg_replace('/<\?xml.*?\?>/', "", $data2); //<?php
            $request = simplexml_load_string($data2);

            if ($received !== null) {
                //if (isset($received->VehAvailRSCore)) {
                if (isset($received->VehAvailRSCore)) {
                    $this->_type = 'pesquisar';
                    $this->parsePesquisar($received);
                } else {
                    if (isset($received->VehResRSCore)) {
                        $this->_type = 'reservar';
                        $this->parseReservar($request, $received);
                    } else {
                        if ($received->VehRetResRSCore) {
                            $this->_type = 'visualizar';
                            $this->parseVisualizar($received);
                        } else {
                            if ($received->VehCancelRSCore) {
                                $this->_type = 'cancelar';
                                $this->parseCancelar($received);
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
                if ($received->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail->VehAvailCore["Status"] == "Available") {
                    $this->_is_ok = 1;
                    $core = $received->VehAvailRSCore;
                    $rentalcore = get_object_vars($core->VehRentalCore);
                    $this->_arr_dados['ReturnDateTime'] = $rentalcore['@attributes']['ReturnDateTime'];
                    $this->_arr_dados['PickUpDateTime'] = $rentalcore['@attributes']['PickUpDateTime'];
                    $pickuploc = get_object_vars($rentalcore['PickUpLocation']);
                    $this->_arr_dados['PickUpLocation'] = $pickuploc['@attributes']['LocationCode'];
                    $returnloc = get_object_vars($rentalcore['ReturnLocation']);
                    $this->_arr_dados['ReturnLocation'] = $returnloc['@attributes']['LocationCode'];
                    $vendoravails = null;
                    foreach ($core->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail as $vendav) {
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
                        $rentalrate_rate = get_object_vars($rentalrate['RateQualifier']);
                        $totalrate = get_object_vars($vehicleavail['TotalCharge']);
                        $taxas_arr = null;
                        $rates_arr = null;
                        foreach ($rentalrate_charges->VehicleCharge as $charge) {
                            $charge_unit = get_object_vars($charge);
                            if ((int)$charge_unit['@attributes']['Purpose'] == 1 || ((int)$charge_unit['@attributes']['Purpose'] == 11 && $charge_unit['@attributes']['Description'] == 'HORAS EXTRAS')) {
                                $rentalrate_calculation = get_object_vars($charge->Calculation);
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
                            } else {
                                $rentalrate_calculation = get_object_vars($charge->Calculation);
                                if ($charge_unit['@attributes']['Purpose'] == 11 && $charge_unit['@attributes']['Description'] != 'HORAS EXTRAS') {
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
                                        'Percentage'            => null,
                                        'Quantity'              => (isset($rentalrate_calculation['@attributes']['Quantity']))?$rentalrate_calculation['@attributes']['Quantity']:NULL,
                                        'Total'                 => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL
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
                                'UnitCharge'   => $coverage_charge['@attributes']['Amount'] / $coverage_calculation['@attributes']['Quantity'],
                                'Quantity'     => $coverage_calculation['@attributes']['Quantity'],
                                'Total'        => $coverage_charge['@attributes']['Amount'],
                                'Included'     => ($coverage_charge['@attributes']['IncludedInRate'] == 'true') ? true : false
                            );

                        }
                        //}
                        $opcionais_arr = null;
                        if($opcionais_info->PricedEquip) {
                            foreach ($opcionais_info->PricedEquip as $opp) {
                                $opp_unit = get_object_vars($opp);
                                $opcional = get_object_vars($opp_unit['Equipment']);
                                $opcional_charge = get_object_vars($opp_unit['Charge']);
                                $opcional_calculation = get_object_vars($opcional_charge['Calculation']);
                                $opcionais_arr[$opcional['@attributes']['EquipType']][] = array(
                                    'EquipType' => $opcional['@attributes']['EquipType'],
                                    'Description' => $opcional['Description'],
                                    'UnitCharge' => $opcional_calculation['@attributes']['UnitCharge'],
                                    'UnitName' => $opcional_calculation['@attributes']['UnitName'],
                                    'Quantity' => $opcional_calculation['@attributes']['Quantity'],
                                    'Total' => ((int)$opcional_calculation['@attributes']['UnitCharge'] * (int)$opcional_calculation['@attributes']['Quantity']),
                                    'Included' => ($opcional_charge['@attributes']['IncludedInRate'] == 'true') ? true : false
                                );

                            }
                        }
                        $code_xml = strtoupper($vehicle['@attributes']['Code']);
                        $vendoravails[$code_xml]['CodeXml'] = $code_xml;
                        $vendoravails[$code_xml]['PickUpLocation'] = $pickuploc['@attributes']['LocationCode'];
                        $vendoravails[$code_xml]['ReturnLocation'] = $returnloc['@attributes']['LocationCode'];
                        $vendoravails[$code_xml]['IdPickUpLocation'] = $this->_parser_data['idReti'];
                        $vendoravails[$code_xml]['IdReturnLocation'] = $this->_parser_data['idDevo'];
                        $vendoravails[$code_xml]['RatePeriod'] = (isset($vendavail['@attributes']['RatePeriod']))?$vendavail['@attributes']['RatePeriod']:NULL;
                        $vendoravails[$code_xml]['RateCategory'] = (isset($vendavail['@attributes']['RateCategory']))?$vendavail['@attributes']['RateCategory']:NULL;
                        $vendoravails[$code_xml]['AirConditionInd'] = (isset($vehicle['@attributes']['AirConditionInd']))?$vehicle['@attributes']['AirConditionInd']:NULL;
                        $vendoravails[$code_xml]['TransmissionType'] = (isset($vehicle['@attributes']['TransmissionType']))?$vehicle['@attributes']['TransmissionType']:NULL;
                        $vendoravails[$code_xml]['Description'] = (isset($vehicle['@attributes']['Description']))?$vehicle['@attributes']['Description']:NULL;
                        $vendoravails[$code_xml]['BaggageQuantity'] = (isset($vehicle['@attributes']['BaggageQuantity']))?$vehicle['@attributes']['BaggageQuantity']:NULL;
                        $vendoravails[$code_xml]['PassengerQuantity'] = (isset($vehicle['@attributes']['PassengerQuantity']))?$vehicle['@attributes']['PassengerQuantity']:NULL;
                        $vendoravails[$code_xml]['Code'] = (isset($vehicle['@attributes']['Code']))?$vehicle['@attributes']['Code']:NULL;
                        $vendoravails[$code_xml]['BaggageQuantity'] = (isset($vehicle['@attributes']['BaggageQuantity']))?$vehicle['@attributes']['BaggageQuantity']:NULL;
                        $vendoravails[$code_xml]['VehicleCategory'] = (isset($vehicle_type['@attributes']['VehicleCategory']))?$vehicle_type['@attributes']['VehicleCategory']:NULL;
                        $vendoravails[$code_xml]['DoorCount'] = (isset($vehicle_type['@attributes']['DoorCount']))?$vehicle_type['@attributes']['DoorCount']:NULL;
                        $vendoravails[$code_xml]['Size'] = (isset($vehicle_classe['@attributes']['Size']))?$vehicle_classe['@attributes']['Size']:NULL;
                        $vendoravails[$code_xml]['Modelo'] = (isset($vehicle_modelo['@attributes']['Name']))?$vehicle_modelo['@attributes']['Name']:NULL;
                        $vendoravails[$code_xml]['RateDistance_Unlimited'] = (isset($rentalrate_distance['@attributes']['Unlimited']))?$rentalrate_distance['@attributes']['Unlimited']:NULL;
                        $vendoravails[$code_xml]['RateDistance_DistUnitName'] = (isset($rentalrate_distance['@attributes']['DistUnitName']))?$rentalrate_distance['@attributes']['DistUnitName']:NULL;
                        $vendoravails[$code_xml]['RateDistance_VehiclePeriodUnitName'] = (isset($rentalrate_distance['@attributes']['VehiclePeriodUnitName']))?$rentalrate_distance['@attributes']['VehiclePeriodUnitName']:NULL;
                        $vendoravails[$code_xml]['RateDistance_Quantity'] = (isset($rentalrate_distance['@attributes']['Quantity']))?$rentalrate_distance['@attributes']['Quantity']:NULL;
                        $vendoravails[$code_xml]['VehicleCharges'] = $rates_arr;
                        $vendoravails[$code_xml]['Rate_RatePeriod'] = (isset($rentalrate_rate['@attributes']['RatePeriod']))?$rentalrate_rate['@attributes']['RatePeriod']:NULL;
                        $vendoravails[$code_xml]['Rate_RateQualifier'] = (isset($rentalrate_rate['@attributes']['RateQualifier']))?$rentalrate_rate['@attributes']['RateQualifier']:NULL;
                        $vendoravails[$code_xml]['Rate_RateCategory'] = (isset($rentalrate_rate['@attributes']['RateCategory']))?$rentalrate_rate['@attributes']['RateCategory']:NULL;
                        $vendoravails[$code_xml]['TotalCharge_RateTotalAmount'] = (isset($totalrate['@attributes']['RateTotalAmount']))?$totalrate['@attributes']['RateTotalAmount']:NULL;
                        $vendoravails[$code_xml]['TotalCharge_EstimatedTotalAmount'] = (isset($totalrate['@attributes']['EstimatedTotalAmount']))?$totalrate['@attributes']['EstimatedTotalAmount']:NULL;
                        $vendoravails[$code_xml]['TotalCharge_CurrencyCode'] = (isset($totalrate['@attributes']['CurrencyCode']))?$totalrate['@attributes']['CurrencyCode']:NULL;
                        $vendoravails[$code_xml]['TotalCharge_DecimalPlaces'] = (isset($totalrate['@attributes']['DecimalPlaces']))?$totalrate['@attributes']['DecimalPlaces']:NULL;
                        $vendoravails[$code_xml]['TotalCharge_RateConvertInd'] = (isset($totalrate['@attributes']['RateConvertInd']))?$totalrate['@attributes']['RateConvertInd']:NULL;
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
                $o = $received->VehResRSCore->VehReservation->VehSegmentCore;
                for ($i = 0, $s = count($o->PricedEquips); $i < $s; $i++) {
                    if ($o->PricedEquips[$i]->PricedEquip->Equipment["EquipType"] == "OWF") {
                        $this->_devolucao = array();
                        $this->_devolucao["Amount"] = $o->PricedEquips[$i]->PricedEquip->Charge["Amount"];
                        $this->_devolucao["Taxa"] = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Total"];
                        $this->_devolucao["Percentage"] = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Percentage"];
                        $this->_devolucao["UnitCharge"] = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitCharge"];
                        $this->_devolucao["UnitName"] = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitName"];
                    } else {
                        if ($o->PricedEquips[$i]->PricedEquip->Equipment["EquipType"] == "8") {
                            $this->_cadeira_bebe = array();
                            $this->_cadeira_bebe["Amount"] = $o->PricedEquips[$i]->PricedEquip->Charge["Amount"];
                            $this->_cadeira_bebe["Total"] = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Total"];
                            $this->_cadeira_bebe["Percentage"] = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Percentage"];
                            $this->_cadeira_bebe["UnitCharge"] = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitCharge"];
                            $this->_cadeira_bebe["UnitName"] = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitName"];
                        } else {
                            if ($o->PricedEquips[$i]->PricedEquip->Equipment["EquipType"] == "13") {
                                $this->_gps = array();
                                $this->_gps["Amount"] = $o->PricedEquips[$i]->PricedEquip->Charge["Amount"];
                                $this->_gps["Total"] = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Total"];
                                $this->_gps["Percentage"] = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Percentage"];
                                $this->_gps["UnitCharge"] = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitCharge"];
                                $this->_gps["UnitName"] = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitName"];
                            }
                        }
                    }
                }
            } else {
                $this->_is_ok = 0;
            }
        }

        private function parseVisualizar($received)
        {
            $this->_is_ok = 0;
            if (isset($received->Body->OTA_VehRetResRS->Success)) {
                $this->_is_ok = 1;
                $this->_xml_id = $received->VehRetResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
                $this->_status_reserva = $received->VehRetResRSCore->VehReservation->VehSegmentCore->ConfID["Status"];
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