<?php
require_once WS_PATH . 'xml_handler.php';

Final Class XML_Handler_Maggi extends XML_Handler
{

	public function __construct($received_data, $request_data) {
		$this->_haw_received_data = $received_data;
		$this->_haw_request_data = $request_data;
		/**
		 * Troca padrão de codificação SOAP para padrão de codificação XML aceitável em simplexml_load_string();
		 */
		$data = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>", $this->_haw_received_data); //<?php
		$data = preg_replace('/<\?xml.*?\?>/', "", $data); //<?php
        $data = preg_replace('/xs:/', "", $data); //<?php
        $data = preg_replace('/diffgr:/', "", $data); //<?php

		$data_rq = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>", $this->_haw_request_data);
		$data_rq = preg_replace('/<\?xml.*?\?>/', "", $data_rq);
		$received = simplexml_load_string($data);
		$request = simplexml_load_string($data_rq);
//        var_Dump($received);
		if (isset($received->Body->GetInformacoesTarifaResponse)) {
			$this->_type = 'pesquisar';
			$this->parsePesquisar($received);
		} else if ($received->Body->GravaReservaResponse) {
			$this->_type = 'reservar';
			$this->parseReservar($received);
		} else if ($received->Body->PesquisaReservaResponse) {
			$this->_type = 'visualizar';
			$this->parseVisualizar($received);
		} else if ($received->Body->CancelaReservaResponse) {
			$this->_type = 'cancelar';
			$this->parseCancelar($received);
		} else {
			$this->_is_ok = 2;
		}
	}

	private function parsePesquisar($received) {
		$this->_is_ok = 0;
		$resrates_att = $received->Body->GetInformacoesTarifaResponse->GetInformacoesTarifaResult->diffgram->NewDataSet;
		if ($resrates_att->Tarifas) {
			$this->_is_ok = 1;
			$vendoravails = NULL;
			$grupos_arr = [];
			foreach ($resrates_att->Tarifas as $vendav) {
                //var_Dump($vendav);
				$code_xml = strtoupper($vendav->SippCode);
				if($vendav->Status_Disponibilidade == 'Available') {
                    $grupos_arr[(string)$vendav->Id_GrupoVeiculo] = strtoupper($vendav->SippCode);
				    if($vendav->VlrHoraExcedenteTotal > 0) {
                        $taxas_arr[13] = array(
                            'IncludedInRate' => NULL,
                            'RateConvertInd' => NULL,
                            'IncludedInEstTotalInd' => NULL,
                            'Description' => 'Hora Extra',
                            'DecimalPlaces' => 2,
                            'CurrencyCode' => 'R$',
                            'TaxInclusive' => NULL,
                            'Amount' => (string)$vendav->VlrHoraExcedente,
                            'Purpose' => 13,
                            'Percentage' => null,
                            'Quantity' => (string)$vendav->Qtde_HoraExcedente,
                            'Total' => (string)$vendav->VlrHoraExcedenteTotal
                        );
                    }
					$vendoravails[$code_xml]['RatePeriod'] = NULL;
					$vendoravails[$code_xml]['RateCategory'] = NULL;
					$vendoravails[$code_xml]['AirConditionInd'] = (string)$vendav->ArCondicionado;
					$vendoravails[$code_xml]['TransmissionType'] = (string)$vendav->Cambio;
					$vendoravails[$code_xml]['Description'] = (string)$vendav->Complemento;
					$vendoravails[$code_xml]['BaggageQuantity'] = (string)$vendav->QtdeMalasGrandes;
					$vendoravails[$code_xml]['PassengerQuantity'] = (string)$vendav->QtdePassageiros;
					$vendoravails[$code_xml]['Code'] = strtoupper($vendav->SippCode);
					$vendoravails[$code_xml]['BaggageQuantity'] = (string)$vendav->QtdeMalasGrandes;
					$vendoravails[$code_xml]['VehicleCategory'] = (string)$vendav->Descricao;
					$vendoravails[$code_xml]['DoorCount'] = (string)$vendav->QtdePortas;
					$vendoravails[$code_xml]['Size'] = NULL;
					$vendoravails[$code_xml]['Modelo'] = (string)$vendav->Complemento;
					$vendoravails[$code_xml]['RateDistance_Unlimited'] = (string)$vendav->LimiteKm;
					$vendoravails[$code_xml]['RateDistance_DistUnitName'] = (string)$vendav->UnidadeMedidaKm;
					$vendoravails[$code_xml]['RateDistance_VehiclePeriodUnitName'] = (string)$vendav->LimiteKm;
					$vendoravails[$code_xml]['VehicleCharges'] = (string)$vendav->VlrDiaria;
					$vendoravails[$code_xml]['Rate_RatePeriod'] = NULL;
					$vendoravails[$code_xml]['Rate_RateQualifier'] = trim($vendav->Id_Tarifa);
					$vendoravails[$code_xml]['Rate_RateCategory'] = NULL;
					$vendoravails[$code_xml]['MinimumDayInd'] = NULL;
					$vendoravails[$code_xml]['MaximumDayInd'] = NULL;
					$vendoravails[$code_xml]['TotalCharge_RateTotalAmount'] = (string)$vendav->VlrTotal;
					$vendoravails[$code_xml]['TotalCharge_EstimatedTotalAmount'] = (string)$vendav->VlrDiariaTotal;
					$vendoravails[$code_xml]['TotalCharge_CurrencyCode'] = (string)$vendav->Tipo_Moeda;
					$vendoravails[$code_xml]['TotalCharge_DecimalPlaces'] = NULL;
					$vendoravails[$code_xml]['TotalCharge_RateConvertInd'] = NULL;
					$vendoravails[$code_xml]['Fees'] = $taxas_arr;
					$vendoravails[$code_xml]['References_ID'] = NULL;
					$vendoravails[$code_xml]['References_Type'] = NULL;
					$vendoravails[$code_xml]['Coverage'] = NULL;
					$vendoravails[$code_xml]['DropCharge'] = NULL;
					$vendoravails[$code_xml]['Availability '] = (string)$vendav->Status_Disponibilidade;
				}
			}
			foreach ($resrates_att->Opcionais as $oppav){
			    if((string)$oppav->Codigo_Opcionais_ACRISS == '') {
                    $opp_arr = [
                        'id' => (string)$oppav->Id_Opcionais,
                        'desc' => (string)$oppav->Descricao,
                        'groupId' => (string)$oppav->Id_GrupoVeiculo,
                        'groupName' => (string)$oppav->GrupoVeiculo,
                        'quantity' => (string)$oppav->Quantidade,
                        'amountDaily' => (string)$oppav->VlrAcessorioDiaria,
                        'amountTotal' => (string)$oppav->VlrAcessorioTotal,
                        'includedInRate' => ((string)$oppav->SomaReserva == 'S') ? true : false,
                        'required' => ((string)$oppav->Obrigatorio == 'true') ? true : false,
                        'codOta' => (string)$oppav->Codigo_Opcionais_OTA,
                        'codACRISS' => (string)$oppav->Codigo_Opcionais_ACRISS,
                    ];
                    $vendoravails[$grupos_arr[(string)$oppav->Id_GrupoVeiculo]]['Optionals'][(string)$oppav->Id_Opcionais] = $opp_arr;
                }else{
                    $coverage_arr = array(
                        'CoverageType' => (string)$oppav->Codigo_Opcionais_OTA,
                        'Details' => (string)$oppav->Descricao,
                        'UnitCharge' => (string)$oppav->VlrAcessorioDiaria,
                        'Quantity' => (string)$oppav->Quantidade,
                        'Total' => (string)$oppav->VlrAcessorioTotal,
                    );
                    $vendoravails[$grupos_arr[(string)$oppav->Id_GrupoVeiculo]]['Coverage'][(string)$oppav->Codigo_Opcionais_OTA] = $coverage_arr;
                }
            }
            foreach ($resrates_att->Taxas as $txav){
                $txav_arr = [
                    'IncludedInRate' => ((string)$txav->SomaReserva == 'S')?true:false,
                    'RateConvertInd' => NULL,
                    'IncludedInEstTotalInd' => NULL,
                    'Description' => (string)$txav->Descricao,
                    'DecimalPlaces' => 2,
                    'CurrencyCode' => 'R$',
                    'TaxInclusive' => NULL,
                    'Amount' => (string)$txav->ValorTotalTaxa,
                    'Purpose' => (string)$txav->Id_Taxa,
                    'Percentage' => (string)$txav->VlrPercTaxa,
                    'Quantity' => NULL,
                    'Total' => (string)$txav->ValorTotalTaxa,
                ];
                $vendoravails[$grupos_arr[(string)$txav->Id_GrupoVeiculo]]['Fees'][(string)$txav->Id_Taxa] = $txav_arr;
            }
            $this->_arr_dados['vendoravail'] = $vendoravails;
		}
	}

	private function parseReservar($received) {
		$reserva = $received->Body->GravaReservaResponse ->GravaReservaResult->diffgram->NewDataSet->Reserva;
		if ($reserva->StatusReserva == 'CONFIRMADA') {
			$this->_is_ok = 1;
			$this->_xml_id = (string)$reserva->NumeroReserva;
            $this->_diaria_sem_taxa = floatval($reserva->VlrDiaria);
            $this->_taxas_diaria = floatval($reserva->VlrTaxaAdministrativa);
            $this->_valor_total = floatval($reserva->VlrTotal);
		}else{
			$this->_is_ok = 0;
		}
	}

	private function parseVisualizar($received) {
		$this->_is_ok = 0;
        $reserva = $received->Body->PesquisaReservaResponse ->PesquisaReservaResult->diffgram->NewDataSet->Reserva;
		if ($reserva->NumeroReserva) {
			$this->_is_ok = 1;
			$this->_status_reserva = (string)$reserva->StatusReserva;
			$this->_xml_id = (string)$reserva->NumeroReserva;
		}
	}

	private function parseCancelar($received) {
		$this->_is_ok = 0;
        $reserva = $received->Body->CancelaReservaResponse->CancelaReservaResult->diffgram->NewDataSet->Erros_Avisos;
		if ($reserva->ReservaCancelada == 'SIM'){
			$this->_is_ok = 1;
			$this->_vis_errorTrack = "Reserva cancelada com sucesso";
		}
	}
}