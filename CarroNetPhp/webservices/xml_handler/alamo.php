<?php

require_once WS_PATH . 'xml_handler.php';

Final Class XML_Handler_Alamo extends XML_Handler
{

    public function __construct($received_data, $request_data, $parser_data)
    {

        $this->_haw_received_data = $received_data;
        $this->_haw_request_data = $request_data;
        $this->_parser_data = $parser_data;

        /**
         * Troca padrão de codificação SOAP para padrão de codificação XML aceitável em simplexml_load_string();
         */
        $data = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>", $this->_haw_received_data); //<?php
        $data = preg_replace('/<\?xml.*?\?>/', "", $data); //<?php

        $received = simplexml_load_string($data);

        // var_dump($received);
        if (isset($received->Body->OtaVehAvailRateResponse->OtaVehAvailRateResult)) {
            $this->_type = 'pesquisar';
            $this->parsePesquisar($received);
        } else {
            if (isset($received->Body->OtaVehResResponse->OtaVehResResult)) {
                $this->_type = 'reservar';
                $this->parseReservar($request, $received);
            } else {
                if (isset($received->Body->OtaVehRetResResponse->OtaVehRetResResult)) {
                    $this->_type = 'visualizar';
                    $this->parseVisualizar($received);
                } else {
                    if (isset($received->Body->OtaVehCancelResponse->OtaVehCancelResult)) {
                        $this->_type = 'cancelar';
                        $this->parseCancelar($received);
                    } else {
                        $this->_is_ok = 2;
                    }
                }
            }
        }
    }

    private function parsePesquisar($received)
    {
        $this->_is_ok = 0;
        if (isset($received->Body->OtaVehAvailRateResponse->OtaVehAvailRateResult->Success)) {
            if (1 == 1) {
                $this->_is_ok = 1;
                $core = $received->Body->OtaVehAvailRateResponse->OtaVehAvailRateResult->VehAvailRSCore;
                $rentalcore = get_object_vars($core->VehRentalCore);
                $this->_arr_dados['ReturnDateTime'] = $rentalcore['@attributes']['ReturnDateTime'];
                $this->_arr_dados['PickUpDateTime'] = $rentalcore['@attributes']['PickUpDateTime'];
                $pickuploc = get_object_vars($rentalcore['PickUpLocation']);
                $this->_arr_dados['PickUpLocation'] = $pickuploc['@attributes']['LocationCode'];
                $returnloc = get_object_vars($rentalcore['ReturnLocation']);
                $this->_arr_dados['ReturnLocation'] = $returnloc['@attributes']['LocationCode'];
                $vendoravails = null;
                foreach ($core->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail as $vendav) {
                    if($vendav->VehAvailCore["Status"] == "Available") {
                        $vendavail = get_object_vars($vendav);
                        $vehicleavail = get_object_vars($vendavail['VehAvailCore']);
                        $vehicleavail_info = get_object_vars($vendavail['VehAvailInfo']);
                        $coverage_info = $vehicleavail_info['PricedCoverages'];
                        $vehicle = get_object_vars($vehicleavail['Vehicle']);
                        //$vehicle_type = get_object_vars($vehicle['VehType']);
                        //$vehicle_group = get_object_vars($vehicle['VehGroup']);
                        //$vehicle_classe = get_object_vars($vehicle['VehClass']);
                        $vehicle_modelo = get_object_vars($vehicle['VehMakeModel']);
                        $rentalrate = get_object_vars($vehicleavail['RentalRate']);
                        $rentalrate_distance = get_object_vars($rentalrate['RateDistance']);
                        $rentalrate_charges = $rentalrate['VehicleCharges'];
                        $rentalrate_rate = get_object_vars($rentalrate['RateQualifier']);
                        $totalrate = get_object_vars($vehicleavail['TotalCharge']);
                        $taxas_arr = null;
                        $taxas = $vehicleavail['Fees'];
                        $taxas_arr = null;
                        $rates_arr = null;
                        foreach ($taxas->Fee as $txs) {
                            $taxas_unit = get_object_vars($txs);
                            $calc_c = get_object_vars($txs->Calculation);
                            $calc = ($calc_c) ? $calc_c : null;
                            if ($taxas_unit['@attributes']['Purpose'] == '11') {
                                $rates_arr[$taxas_unit['@attributes']['Purpose']] = array(
                                    'VehicleCharge_IncludedInRate' => (isset($taxas_unit['@attributes']['IncludedInRate']))?$taxas_unit['@attributes']['IncludedInRate']:NULL,
                                    'VehicleCharge_RateConvertInd' => (isset($taxas_unit['@attributes']['RateConvertInd']))?$taxas_unit['@attributes']['RateConvertInd']:NULL,
                                    'VehicleCharge_IncludedInEstTotalInd' => (isset($taxas_unit['@attributes']['IncludedInEstTotalInd']))?$taxas_unit['@attributes']['IncludedInEstTotalInd']:NULL,
                                    'VehicleCharge_Description' => (isset($taxas_unit['@attributes']['Description']))?$taxas_unit['@attributes']['Description']:NULL,
                                    'VehicleCharge_DecimalPlaces' => (isset($taxas_unit['@attributes']['DecimalPlaces']))?$taxas_unit['@attributes']['DecimalPlaces']:NULL,
                                    'VehicleCharge_CurrencyCode' => (isset($taxas_unit['@attributes']['CurrencyCode']))?$taxas_unit['@attributes']['CurrencyCode']:NULL,
                                    'VehicleCharge_TaxInclusive' => (isset($taxas_unit['@attributes']['TaxInclusive']))?$taxas_unit['@attributes']['TaxInclusive']:NULL,
                                    'VehicleCharge_Amount' => (isset($taxas_unit['@attributes']['Amount']))?$taxas_unit['@attributes']['Amount']:NULL,
                                    'VehicleCharge_Purpose' => (isset($taxas_unit['@attributes']['Purpose']))?$taxas_unit['@attributes']['Purpose']:NULL,
                                    //'Calculation_UnitCharge' => $rentalrate_calculation['@attributes']['UnitCharge'],
                                    //'Calculation_UnitName' => $rentalrate_calculation['@attributes']['UnitName'],
                                    'Calculation_Quantity' => ($calc) ? $calc['@attributes']['Quantity'] : null,
                                    'Calculation_Total' => $taxas_unit['@attributes']['Amount']
                                );
                            } else {

                                $taxas_arr[$taxas_unit['@attributes']['Purpose']] = array(
                                    'IncludedInRate' => (isset($taxas_unit['@attributes']['IncludedInRate']))?$taxas_unit['@attributes']['IncludedInRate']:NULL,
                                    'RateConvertInd' => (isset($taxas_unit['@attributes']['RateConvertInd']))?$taxas_unit['@attributes']['RateConvertInd']:NULL,
                                    'IncludedInEstTotalInd' => (isset($taxas_unit['@attributes']['IncludedInEstTotalInd']))?$taxas_unit['@attributes']['IncludedInEstTotalInd']:NULL,
                                    'Description' => (isset($taxas_unit['@attributes']['Description']))?$taxas_unit['@attributes']['Description']:NULL,
                                    'DecimalPlaces' => (isset($taxas_unit['@attributes']['DecimalPlaces']))?$taxas_unit['@attributes']['DecimalPlaces']:NULL,
                                    'CurrencyCode' => (isset($taxas_unit['@attributes']['CurrencyCode']))?$taxas_unit['@attributes']['CurrencyCode']:NULL,
                                    'TaxInclusive' => (isset($taxas_unit['@attributes']['TaxInclusive']))?$taxas_unit['@attributes']['TaxInclusive']:NULL,
                                    'Amount' => (isset($taxas_unit['@attributes']['Amount']))?$taxas_unit['@attributes']['Amount']:NULL,
                                    'Purpose' => (isset($taxas_unit['@attributes']['Purpose']))?$taxas_unit['@attributes']['Purpose']:NULL,
                                    'Percentage' => ($calc) ? ((isset($calc['@attributes']['Percentage']))?$calc['@attributes']['Percentage']:NULL) : null,
                                    'Total' => ($calc) ? ((isset($calc['@attributes']['Total']))?$calc['@attributes']['Total']:NULL) : null,
                                );
                            }
                        }

                        foreach ($rentalrate_charges->VehicleCharge as $charge) {
                            $charge_unit = get_object_vars($charge);
                            if ((int)$charge_unit['@attributes']['Purpose'] == 1) {
                                $rentalrate_calculation = get_object_vars($charge->Calculation);
                                $rates_arr[$charge_unit['@attributes']['Purpose']] = array(
                                    'VehicleCharge_IncludedInRate' => (isset($charge_unit['@attributes']['IncludedInRate']))?$charge_unit['@attributes']['IncludedInRate']:NULL,
                                    'VehicleCharge_RateConvertInd' => (isset($charge_unit['@attributes']['RateConvertInd']))?$charge_unit['@attributes']['RateConvertInd']:NULL,
                                    'VehicleCharge_IncludedInEstTotalInd' => (isset($charge_unit['@attributes']['IncludedInEstTotalInd']))?$charge_unit['@attributes']['IncludedInEstTotalInd']:NULL,
                                    'VehicleCharge_Description' => (isset($charge_unit['@attributes']['Description']))?$charge_unit['@attributes']['Description']:NULL,
                                    'VehicleCharge_DecimalPlaces' => (isset($charge_unit['@attributes']['DecimalPlaces']))?$charge_unit['@attributes']['DecimalPlaces']:NULL,
                                    'VehicleCharge_CurrencyCode' => (isset($charge_unit['@attributes']['CurrencyCode']))?$charge_unit['@attributes']['CurrencyCode']:NULL,
                                    'VehicleCharge_TaxInclusive' => (isset($charge_unit['@attributes']['TaxInclusive']))?$charge_unit['@attributes']['TaxInclusive']:NULL,
                                    'VehicleCharge_Amount' => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL,
                                    'VehicleCharge_Purpose' => (isset($charge_unit['@attributes']['Purpose']))?$charge_unit['@attributes']['Purpose']:NULL,
                                    'Calculation_UnitCharge' => (isset($rentalrate_calculation['@attributes']['UnitCharge']))?$rentalrate_calculation['@attributes']['UnitCharge']:NULL,
                                    'Calculation_UnitName' => (isset($rentalrate_calculation['@attributes']['UnitName']))?$rentalrate_calculation['@attributes']['UnitName']:NULL,
                                    'Calculation_Quantity' => (isset($rentalrate_calculation['@attributes']['Quantity']))?$rentalrate_calculation['@attributes']['Quantity']:NULL,
                                    'Calculation_Total' => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL
                                );
                                if (isset($charge_unit['TaxAmounts'])) {
                                    foreach ($charge_unit['TaxAmounts'] as $tax_dia) {
                                        $taxa_diaria = get_object_vars($tax_dia);
                                        $tax_code = ($taxa_diaria['@attributes']['Description'] == 'TARIFA REGIONAL') ? 1000 : 1001;
                                        $taxas_arr[$tax_code] = [
                                            'IncludedInRate' => (isset($taxa_diaria['@attributes']['IncludedInRate']))?$taxa_diaria['@attributes']['IncludedInRate']:NULL,
                                            'RateConvertInd' => (isset($taxa_diaria['@attributes']['RateConvertInd']))?$taxa_diaria['@attributes']['RateConvertInd']:NULL,
                                            'IncludedInEstTotalInd' => (isset($taxa_diaria['@attributes']['IncludedInEstTotalInd']))?$taxa_diaria['@attributes']['IncludedInEstTotalInd']:NULL,
                                            'Description' => (isset($taxa_diaria['@attributes']['Description']))?$taxa_diaria['@attributes']['Description']:NULL,
                                            'DecimalPlaces' => (isset($taxa_diaria['@attributes']['DecimalPlaces']))?$taxa_diaria['@attributes']['DecimalPlaces']:NULL,
                                            'CurrencyCode' => (isset($taxa_diaria['@attributes']['CurrencyCode']))?$taxa_diaria['@attributes']['CurrencyCode']:NULL,
                                            'TaxInclusive' => (isset($taxa_diaria['@attributes']['TaxInclusive']))?$taxa_diaria['@attributes']['TaxInclusive']:NULL,
                                            'Amount' => (isset($taxa_diaria['@attributes']['Total']))?$taxa_diaria['@attributes']['Total']:NULL,
                                            'Purpose' => $tax_code,
                                            'Percentage' => (isset($taxa_diaria['@attributes']['Percentage']))?$taxa_diaria['@attributes']['Percentage']:NULL,
                                            'Total' => (isset($taxa_diaria['@attributes']['Total']))?$taxa_diaria['@attributes']['Total']:NULL,
                                        ];
                                    }
                                }
                            } else {
                                $rentalrate_calculation = get_object_vars($charge->Calculation);

                                if ($charge_unit['@attributes']['Purpose'] != 13 && $charge_unit['@attributes']['Purpose'] != 28) {
                                    $taxas_arr[$charge_unit['@attributes']['Purpose']] = array(
                                        'IncludedInRate' => (isset($charge_unit['@attributes']['IncludedInRate']))?$charge_unit['@attributes']['IncludedInRate']:NULL,
                                        'RateConvertInd' => (isset($charge_unit['@attributes']['RateConvertInd']))?$charge_unit['@attributes']['RateConvertInd']:NULL,
                                        'IncludedInEstTotalInd' => (isset($charge_unit['@attributes']['IncludedInEstTotalInd']))?$charge_unit['@attributes']['IncludedInEstTotalInd']:NULL,
                                        'Description' => (isset($charge_unit['@attributes']['Description']))?$charge_unit['@attributes']['Description']:NULL,
                                        'DecimalPlaces' => (isset($charge_unit['@attributes']['DecimalPlaces']))?$charge_unit['@attributes']['DecimalPlaces']:NULL,
                                        'CurrencyCode' => (isset($charge_unit['@attributes']['CurrencyCode']))?$charge_unit['@attributes']['CurrencyCode']:NULL,
                                        'TaxInclusive' => (isset($charge_unit['@attributes']['TaxInclusive']))?$charge_unit['@attributes']['TaxInclusive']:NULL,
                                        'Amount' => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL,
                                        'Purpose' => (isset($charge_unit['@attributes']['Purpose']))?$charge_unit['@attributes']['Purpose']:NULL,
                                        'Percentage' => null,
                                        'Quantity' => (isset($rentalrate_calculation['@attributes']['Quantity']))?$rentalrate_calculation['@attributes']['Quantity']:NULL,
                                        'Total' => (isset($charge_unit['@attributes']['Amount']))?$charge_unit['@attributes']['Amount']:NULL
                                    );
                                }

                            }
                        }
                        $coverage_arr = null;

                        foreach ($coverage_info->PricedCoverage as $cover) {
                            $cover_unit = get_object_vars($cover);
                            $coverage = get_object_vars($cover_unit['Coverage']);
                            $coverage_charge = get_object_vars($cover_unit['Charge']);
                            $coverage_calculation = get_object_vars($coverage_charge['Calculation']);
                            $coverage_arr[$coverage['@attributes']['CoverageType']] = array(
                                'CoverageType' => $coverage['@attributes']['CoverageType'],
                                'Details' => $coverage['Details'],
                                'UnitCharge' => $coverage_calculation['@attributes']['UnitCharge'],
                                'Quantity' => $coverage_calculation['@attributes']['Quantity'],
                                'Total' => ((float)$coverage_calculation['@attributes']['UnitCharge'] * (float)$coverage_calculation['@attributes']['Quantity']),
                            );
                        }

                        $code_xml = strtoupper($vehicle_modelo['@attributes']['Code']);
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
                        //$vendoravails[$code_xml]['VehicleCategory'] = $vehicle_type['@attributes']['VehicleCategory'];
                        //$vendoravails[$code_xml]['DoorCount'] = $vehicle_type['@attributes']['DoorCount'];
                        //$vendoravails[$code_xml]['Size'] = $vehicle_classe['@attributes']['Size'];
                        $vendoravails[$code_xml]['Modelo'] = (isset($vehicle_modelo['@attributes']['Name']))?$vehicle_modelo['@attributes']['Name']:NULL;
                        $vendoravails[$code_xml]['RateDistance_Unlimited'] = (isset($rentalrate_distance['@attributes']['Unlimited']))?$rentalrate_distance['@attributes']['Unlimited']:NULL;
                        $vendoravails[$code_xml]['RateDistance_DistUnitName'] = (isset($rentalrate_distance['@attributes']['DistUnitName']))?$rentalrate_distance['@attributes']['DistUnitName']:NULL;
                        $vendoravails[$code_xml]['RateDistance_VehiclePeriodUnitName'] = (isset($rentalrate_distance['@attributes']['VehiclePeriodUnitName']))?$rentalrate_distance['@attributes']['VehiclePeriodUnitName']:NULL;
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
                    }
                }
                $this->_arr_dados['vendoravail'] = $vendoravails;
            }
        }
    }

    private function parseReservar($request, $received)
    {
        if ($received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"] != "") {

            $this->_is_ok = 1;
            $this->_xml_id = $received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];

            $calc_prote = floatval($received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentInfo->PricedCoverages->PricedCoverage->Charge["Amount"]);

            $this->_diaria_sem_taxa = floatval($received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["RateTotalAmount"]);
            // $this->_valor_total = floatval($received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["EstimatedTotalAmount"]) + $calc_prote;
            $o = $received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentCore->Fees;
            for ($i = 0, $s = count($o->Fee); $i < $s; $i++) {
                if ($o->Fee[$i]["Description"] != "HORA EXTRA") {
                    $total_taxas = $total_taxas + floatval($o->Fee[$i]["Amount"]);
                } else {
                    $hora_extra = floatval($o->Fee[$i]["Amount"]);
                }
                if ($o->Fee[$i]["Description"] == "TAXA DE RETORNO") {
                    $this->_devolucao = $o->Fee[$i]["Amount"];
                }
            }
            $this->_taxas_diaria = $total_taxas;
            $val_total = floatval($received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["RateTotalAmount"]) + $calc_prote + $hora_extra;
            $total_value = floatval($val_total);
            $this->_valor_total = $total_value;
        } else {
            $this->_is_ok = 0;
        }
    }

    private function parseVisualizar($received)
    {
        $this->_is_ok = 0;
        if (isset($received->Body->OtaVehRetResResponse->OtaVehRetResResult->Success)) {
            if (isset($received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehResSummaries)) {
                $this->_is_ok = 3;
                $this->_xml_id = count($received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehResSummaries->VehResSummary);
                $i = 0;
                foreach ($received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehResSummaries->VehResSummary as $reservas) {
                    $this->_vis_name[$i]->id = $reservas->ConfID['ID'];
                    $this->_vis_name[$i]->status = $reservas['ReservationStatus'];
                    $this->_vis_name[$i]->nome = $reservas->PersonName->GivenName;
                    $this->_vis_name[$i]->loj_retirada = $reservas->PickUpLocation['LocationCode'];
                    $this->_vis_name[$i]->loj_devolucao = $reservas->ReturnLocation['LocationCode'];
                    $this->_vis_name[$i]->date_retirada = $reservas['PickUpDateTime'];
                    $this->_vis_name[$i]->modelo = $reservas->Vehicle->VehMakeModel['Name'];
                    $this->_vis_name[$i]->modelocod = $reservas->Vehicle->VehMakeModel['Code'];
                    $i++;
                }
            } else {
                $this->_is_ok = 1;
                $this->_xml_id = $received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
                $this->_vis_name = $received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehReservation->Customer->Primary->PersonName->GivenName;
                $this->_vis_dtareti = $received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehReservation->VehSegmentCore->VehRentalCore["PickUpDateTime"];
                $this->_vis_dtadevo = $received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehReservation->VehSegmentCore->VehRentalCore["ReturnDateTime"];
                $this->_vis_ljreti = $received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehReservation->VehSegmentInfo->LocationDetails[0]["Name"];
                $this->_vis_ljdevo = $received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehReservation->VehSegmentInfo->LocationDetails[1]["Name"];
                $this->_diaria_sem_taxa = floatval($received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge["Amount"]);
                $this->_taxas_diaria = $received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehReservation->VehSegmentCore->Fees->Fee["Amount"];
                $this->_valor_total = $received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehReservation->VehSegmentCore->TotalCharge["EstimatedTotalAmount"];
                $this->_nmb_diarias = $received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->Calculation["Quantity"];
                $this->_vis_ciaaerea = $received->Body->OtaVehRetResResponse->OtaVehRetResResult->VehRetResRSCore->VehReservation['ReservationStatus'];
            }
        } else {
            $this->_vis_errorTrack = $received->Body->OtaVehRetResResponse->OtaVehRetResResult->Errors->Error;
        }
    }

    private function parseCancelar($received)
    {
        $this->_is_ok = 0;
        if ($received->Body->OtaVehCancelResponse->OtaVehCancelResult->VehCancelRSInfo->VehReservation["ReservationStatus"] == "CAN") {
            $this->_is_ok = 1;
            $this->_vis_errorTrack = "Reserva cancelada com sucesso";
        } else {
            if ($received->Body->OtaVehCancelResponse->OtaVehCancelResult->Errors->Error["Code"] == "95") {
                $this->_is_ok = 0;
                $this->_vis_errorTrack = "Esta reserva já havia sido cancelada!";
            }
        }
    }
}

?>