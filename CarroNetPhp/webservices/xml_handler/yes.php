<?php
    require_once WS_PATH . 'xml_handler.php';

    Final Class XML_Handler_Yes extends XML_Handler
    {

        public function __construct($received_data, $request_data)
        {
            $this->_haw_received_data = $received_data;
            $this->_haw_request_data = $request_data;
            /**
             * Troca padrão de codificação SOAP para padrão de codificação XML aceitável em simplexml_load_string();
             */
            $data = preg_replace('/<\?xml.*?\?>/', "", $this->_haw_received_data); //<?php
            $data = preg_replace('/<!--(.*?)-->/', '', $data);
            $data_rq = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>", $this->_haw_request_data);
            $data_rq = preg_replace('/<\?xml.*?\?>/', "", $data_rq);
            $received = simplexml_load_string($data);
            $request = simplexml_load_string($data_rq);
            if (isset($received->VehAvailRSCore)) {
                $this->_type = 'pesquisar';
                $this->parsePesquisar($received);
            } else {
                if (isset($received->VehResRSCore)) {
                    $this->_type = 'reservar';
                    $this->parseReservar($received);
                } else {
                    if (isset($received->ResStatus)) {
                        $this->_type = 'visualizar';
                        $this->parseVisualizar($received);
                    } else {
                        if (isset($received->VehCancelRSCore)) {
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
            $resrates_att = get_object_vars($received->VehAvailRSCore);
            if ($received->Success) {
                $this->_is_ok = 1;
                $vendoravails = null;
                //var_dump($resrates_att);
                foreach ($resrates_att['VehVendorAvails']->VehVendorAvail->VehAvails->VehAvail as $vendav) {
                    //var_Dump($vendav);
                    $resrates_arr = get_object_vars($vendav->VehAvailCore);
                    $coverage_info = $vendav->VehAvailInfo->PricedCoverages;
                    //var_dump($coverage_info);
                    $resrates_dados = $resrates_arr['RentalRate'][0];
                    $resrates_taxas = $resrates_arr['Fees'];
                    $resrates_distance = get_object_vars($resrates_dados->RateDistance);
                    $resrates_total = get_object_vars($resrates_arr['TotalCharge']);
                    $resvehicle_dados = get_object_vars($resrates_arr['Vehicle']->VehMakeModel);
                    $resrate_qual = get_object_vars($resrates_dados->RateQualifier);
                    $code_xml = strtoupper($resvehicle_dados['@attributes']['Code']);
                    $taxas_arr = null;
                    foreach ($resrates_taxas->Fee as $txs) {
                        $taxas_unit = get_object_vars($txs);
                        $calc_c = get_object_vars($txs->Calculation);
                        $calc = ($calc_c) ? $calc_c : null;
                        $taxas_arr[$taxas_unit['@attributes']['Purpose']] = array(
                            'IncludedInRate'        => (isset($taxas_unit['@attributes']['IncludedInRate']))?$taxas_unit['@attributes']['IncludedInRate']:NULL,
                            'RateConvertInd'        => (isset($taxas_unit['@attributes']['RateConvertInd']))?$taxas_unit['@attributes']['RateConvertInd']:NULL,
                            'IncludedInEstTotalInd' => (isset($taxas_unit['@attributes']['IncludedInEstTotalInd']))?$taxas_unit['@attributes']['IncludedInEstTotalInd']:NULL,
                            'Description'           => (isset($taxas_unit['@attributes']['Description']))?$taxas_unit['@attributes']['Description']:NULL,
                            'DecimalPlaces'         => (isset($taxas_unit['@attributes']['DecimalPlaces']))?$taxas_unit['@attributes']['DecimalPlaces']:NULL,
                            'CurrencyCode'          => (isset($taxas_unit['@attributes']['CurrencyCode']))?$taxas_unit['@attributes']['CurrencyCode']:NULL,
                            'TaxInclusive'          => (isset($taxas_unit['@attributes']['TaxInclusive']))?$taxas_unit['@attributes']['TaxInclusive']:NULL,
                            'Amount'                => (isset($taxas_unit['@attributes']['Amount']))?$taxas_unit['@attributes']['Amount']:NULL,
                            'Purpose'               => (isset($taxas_unit['@attributes']['Purpose']))?$taxas_unit['@attributes']['Purpose']:NULL,
                            'Percentage'            => ($calc) ? $calc['@attributes']['Percentage'] : null,
                            'Total'                 => ($calc) ? $calc['@attributes']['Total'] : null,
                        );
                    }
                    $rates_arr = null;
                    $rate_charges = $resrates_dados->VehicleCharges;
                    foreach ($rate_charges->VehicleCharge as $charge) {
                        $charge_unit = get_object_vars($charge);
                        $rentalrate_calculation = get_object_vars($charge->Calculation);
                        $tax = get_object_vars($charge->TaxAmounts->TaxAmount);
                        $rates_arr[1] = array(
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
                            'Calculation_Total'                   => $rentalrate_calculation['@attributes']['Total']
                        );
                        $taxas_arr[2] = array(
                            'IncludedInRate'        => NULL,
                            'RateConvertInd'        => NULL,
                            'IncludedInEstTotalInd' => NULL,
                            'Description'           => $tax['@attributes']['Description'],
                            'DecimalPlaces'         => NULL,
                            'CurrencyCode'          => $tax['@attributes']['CurrencyCode'],
                            'TaxInclusive'          => NULL,
                            'Amount'                => $tax['@attributes']['Total'],
                            'Purpose'               => 2,
                            'Percentage'            => $tax['@attributes']['Percentage'],
                            'Total'                 => $tax['@attributes']['Total'],
                        );
                    }
                    $coverage_arr = NULL;

                    foreach($coverage_info->PricedCoverage as $cover){
                        $cover_unit = get_object_vars($cover);
                        $coverage = get_object_vars($cover_unit['Coverage']);
                        $coverage_charge = get_object_vars($cover_unit['Charge']);
                        $coverage_calculation = get_object_vars($coverage_charge['Calculation']);
                        $coverage_details = get_object_vars($coverage['Details']);
                        $coverage_arr[$coverage['@attributes']['CoverageType']] = array(
                            'CoverageType' => $coverage['@attributes']['CoverageType'],
                            'Details' => $coverage_details['@attributes']['CoverageTextType'],
                            'UnitCharge' => $coverage_calculation['@attributes']['UnitCharge'],
                            'Quantity' => $coverage_calculation['@attributes']['Quantity'],
                            'Total' => $coverage_charge['@attributes']['Amount'],
                        );
                    }

                    if ($resrates_arr['@attributes']['Status'] == 'Disponível' || $resrates_arr['@attributes']['Status'] == 'Available') {
                        $vendoravails[$code_xml]['CodeXml'] = $code_xml;
                        $vendoravails[$code_xml]['RatePeriod'] = null;
                        $vendoravails[$code_xml]['RateCategory'] = null;
                        $vendoravails[$code_xml]['AirConditionInd'] = null;
                        $vendoravails[$code_xml]['TransmissionType'] = null;
                        $vendoravails[$code_xml]['Description'] = null;
                        $vendoravails[$code_xml]['BaggageQuantity'] = null;
                        $vendoravails[$code_xml]['PassengerQuantity'] = null;
                        $vendoravails[$code_xml]['Code'] = $code_xml;
                        $vendoravails[$code_xml]['BaggageQuantity'] = null;
                        $vendoravails[$code_xml]['VehicleCategory'] = null;
                        $vendoravails[$code_xml]['DoorCount'] = null;
                        $vendoravails[$code_xml]['Size'] = null;
                        $vendoravails[$code_xml]['Modelo'] = $resvehicle_dados['@attributes']['Name'];
                        $vendoravails[$code_xml]['RateDistance_Unlimited'] = $resrates_distance['@attributes']['Unlimited'];
                        $vendoravails[$code_xml]['RateDistance_DistUnitName'] = $resrates_distance['@attributes']['DistUnitName'];
                        $vendoravails[$code_xml]['RateDistance_VehiclePeriodUnitName'] = $resrates_distance['@attributes']['VehiclePeriodUnitName'];
                        $vendoravails[$code_xml]['VehicleCharges'] = $rates_arr;
                        $vendoravails[$code_xml]['Rate_RatePeriod'] = $resrate_qual['@attributes']['RatePeriod'];
                        $vendoravails[$code_xml]['Rate_RateQualifier'] = $resrate_qual['@attributes']['RateQualifier'];
                        $vendoravails[$code_xml]['Rate_RateCategory'] = $resrate_qual['@attributes']['RatePeriod'];
                        $vendoravails[$code_xml]['MinimumDayInd'] = null;
                        $vendoravails[$code_xml]['MaximumDayInd'] = null;
                        $vendoravails[$code_xml]['TotalCharge_RateTotalAmount'] = $resrates_total['@attributes']['RateTotalAmount'];
                        $vendoravails[$code_xml]['TotalCharge_EstimatedTotalAmount'] = $resrates_total['@attributes']['EstimatedTotalAmount'];
                        $vendoravails[$code_xml]['TotalCharge_CurrencyCode'] = $resrates_total['@attributes']['CurrencyCode'];
                        $vendoravails[$code_xml]['TotalCharge_DecimalPlaces'] = null;
                        $vendoravails[$code_xml]['TotalCharge_RateConvertInd'] = null;
                        $vendoravails[$code_xml]['Fees'] = $taxas_arr;
                        $vendoravails[$code_xml]['References_ID'] = null;
                        $vendoravails[$code_xml]['References_Type'] = null;
                        $vendoravails[$code_xml]['Coverage'] = $coverage_arr;
                        $vendoravails[$code_xml]['DropCharge'] = $resrates_dados['DropCharge'];
                        $vendoravails[$code_xml]['Availability '] = $resrates_dados['Availability'];
                    }
                }
                $this->_arr_dados['vendoravail'] = $vendoravails;
            }
        }

        private function parseReservar($received)
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
            $resrates_att = get_object_vars($received->NewReservationResponse);
            if ($resrates_att['@attributes']['success'] == 'true') {
                $this->_is_ok = 1;
                $this->_status_reserva = $resrates_att['@attributes']['status'];
                $this->_xml_id = $resrates_att['@attributes']['reservationNumber'];
            }
        }

        private function parseCancelar($received)
        {
            $this->_is_ok = 0;
            $this->_vis_errorTrack = 'Teste';
            $resrates_att = get_object_vars($received->VehCancelRSCore);
            if ($received->VehCancelRSCore['CancelStatus'] == "Cancelled"){
                $this->_is_ok = 1;
                $this->_vis_errorTrack = "Reserva cancelada com sucesso";
            }
        }
    }