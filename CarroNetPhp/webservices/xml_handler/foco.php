<?php
require_once WS_PATH . 'xml_handler.php';

Final Class XML_Handler_Foco extends XML_Handler
{

	public function __construct($received_data, $request_data) {
		$this->_haw_received_data = $received_data;
		$this->_haw_request_data = $request_data;
		/**
		 * Troca padrão de codificação SOAP para padrão de codificação XML aceitável em simplexml_load_string();
		 */
		$data = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>", $this->_haw_received_data); //<?php
		$data = preg_replace('/<\?xml.*?\?>/', "", $data); //<?php
		$data_rq = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>", $this->_haw_request_data);
		$data_rq = preg_replace('/<\?xml.*?\?>/', "", $data_rq);
		$received = simplexml_load_string($data);
		$request = simplexml_load_string($data_rq);

		if (isset($received->ResRates)) {
			$this->_type = 'pesquisar';
			$this->parsePesquisar($received);
		} else if (isset($received->NewReservationResponse)) {
			$this->_type = 'reservar';
			$this->parseReservar($received);
		} else if (isset($received->ResStatus)) {
			$this->_type = 'visualizar';
			$this->parseVisualizar($received);
		} else if (isset($received->CancelReservationResponse)) {
			$this->_type = 'cancelar';
			$this->parseCancelar($received);
		} else {
			$this->_is_ok = 2;
		}
	}

	private function parsePesquisar($received) {
		$this->_is_ok = 0;
		$resrates_att = get_object_vars($received->ResRates);
		if ($resrates_att['@attributes']['success'] == 'true') {
			$this->_is_ok = 1;
			$vendoravails = NULL;
			foreach ($received->ResRates->Rate as $vendav) {
				$resrates_dados = get_object_vars($vendav);
				$code_xml = strtoupper($vendav->Class);
				if($vendav->Availability == 'Available') {
					$vendoravails[$code_xml]['RatePeriod'] = NULL;
					$vendoravails[$code_xml]['RateCategory'] = NULL;
					$vendoravails[$code_xml]['AirConditionInd'] = NULL;
					$vendoravails[$code_xml]['TransmissionType'] = NULL;
					$vendoravails[$code_xml]['Description'] = NULL;
					$vendoravails[$code_xml]['BaggageQuantity'] = NULL;
					$vendoravails[$code_xml]['PassengerQuantity'] = NULL;
					$vendoravails[$code_xml]['Code'] = strtoupper($vendav->Class);
					$vendoravails[$code_xml]['BaggageQuantity'] = NULL;
					$vendoravails[$code_xml]['VehicleCategory'] = NULL;
					$vendoravails[$code_xml]['DoorCount'] = NULL;
					$vendoravails[$code_xml]['Size'] = NULL;
					$vendoravails[$code_xml]['Modelo'] = NULL;
					$resrates_distance = get_object_vars($resrates_dados['Distance']);
					$vendoravails[$code_xml]['RateDistance_Unlimited'] = $resrates_distance['Included'];
					$vendoravails[$code_xml]['RateDistance_DistUnitName'] = NULL;
					$vendoravails[$code_xml]['RateDistance_VehiclePeriodUnitName'] = NULL;
					$vendoravails[$code_xml]['VehicleCharges'] = NULL;
					$vendoravails[$code_xml]['Rate_RatePeriod'] = NULL;
					$vendoravails[$code_xml]['Rate_RateQualifier'] = trim($vendav->RateID);
					$vendoravails[$code_xml]['Rate_RateCategory'] = NULL;
					$vendoravails[$code_xml]['MinimumDayInd'] = NULL;
					$vendoravails[$code_xml]['MaximumDayInd'] = NULL;
					$vendoravails[$code_xml]['TotalCharge_RateTotalAmount'] = $resrates_dados['Estimate'];
					$vendoravails[$code_xml]['TotalCharge_EstimatedTotalAmount'] = $resrates_dados['RateOnlyEstimate'];
					$vendoravails[$code_xml]['TotalCharge_CurrencyCode'] = $resrates_dados['CurrencyCode'];
					$vendoravails[$code_xml]['TotalCharge_DecimalPlaces'] = NULL;
					$vendoravails[$code_xml]['TotalCharge_RateConvertInd'] = NULL;
					$vendoravails[$code_xml]['Fees'] = NULL;
					$vendoravails[$code_xml]['References_ID'] = NULL;
					$vendoravails[$code_xml]['References_Type'] = NULL;
					$vendoravails[$code_xml]['Coverage'] = NULL;
					$vendoravails[$code_xml]['DropCharge'] = $resrates_dados['DropCharge'];
					$vendoravails[$code_xml]['Availability '] = $resrates_dados['Availability'];
				}
			}
			$this->_arr_dados['vendoravail'] = $vendoravails;
		}
	}

	private function parseReservar($received) {
		$resrates_att = get_object_vars($received->NewReservationResponse);
		if ($resrates_att['@attributes']['success'] == 'true') {
			$this->_is_ok = 1;
			$this->_xml_id = $resrates_att['@attributes']['reservationNumber'];
		}else{
			$this->_is_ok = 0;
		}
	}

	private function parseVisualizar($received) {
		$this->_is_ok = 0;
		$resrates_att = get_object_vars($received->NewReservationResponse);
		if ($resrates_att['@attributes']['success'] == 'true') {
			$this->_is_ok = 1;
			$this->_status_reserva = $resrates_att['@attributes']['status'];
			$this->_xml_id = $resrates_att['@attributes']['reservationNumber'];
		}
	}

	private function parseCancelar($received) {
		$this->_is_ok = 0;
		$this->_vis_errorTrack = 'Teste';
		$resrates_att = get_object_vars($received->CancelReservationResponse);
		if ($resrates_att['@attributes']['success'] == 'true') 	{
			$this->_is_ok = 1;
			$this->_vis_errorTrack = "Reserva cancelada com sucesso";
		}
	}
}