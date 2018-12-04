<?php
require_once WS_PATH . 'xml_handler.php';

Final Class XML_Handler_Jimpisoft extends XML_Handler
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

		if (isset($received->Body->MultiplePricesResponse)) {
			$this->_type = 'pesquisar';
			$this->parsePesquisar($received);
		} else if ($received->NewReservationResponse) {
			$this->_type = 'reservar';
			$this->parseReservar($received);
		} else if ($received->ResStatus) {
			$this->_type = 'visualizar';
			$this->parseVisualizar($received);
		} else if ($received->CancelReservationResponse) {
			$this->_type = 'cancelar';
			$this->parseCancelar($received);
		} else if ($received->Body->getGroupsResponse) {
            $this->_type = 'groups';
            $this->parseGroups($received);
        }else if ($received->Body->GroupDetailsResponse ) {
            $this->_type = 'groups';
            $this->parseGroupsDetails($received);
        } else if ($received->Body->getCitiesResponse) {
            $this->_type = 'cities';
            $this->parseCities($received);
        } else if ($received->Body->getCountriesResponse) {
            $this->_type = 'countries';
            $this->parseCountries($received);
        } else if ($received->Body->getStationsResponse) {
            $this->_type = 'stations';
            $this->parseStations($received);
        } else if ($received->Body->StationDetailsResponse) {
            $this->_type = 'stations_details';
            $this->parseStationsDetails($received);
        } else {
			$this->_is_ok = 2;
		}
	}

	private function parsePesquisar($received) {

		$this->_is_ok = 0;
		$resrates_att = $received->Body->MultiplePricesResponse->MultiplePricesResult->getMultiplePrices->diffgram->NewDataSet;
        if ($resrates_att->MultiplePrices) {
			$this->_is_ok = 1;
			$vendoravails = NULL;
			foreach ($resrates_att->MultiplePrices as $vendav) {
                var_Dump($vendav);
				$code_xml = trim($vendav->groupID);
				if($vendav->previewValueWithDiscount) {
					$vendoravails[$code_xml]['RatePeriod'] = NULL;
					$vendoravails[$code_xml]['RateCategory'] = NULL;
                    $vendoravails[$code_xml]['RateCode'] = (string)$vendav->rateCode;
					$vendoravails[$code_xml]['AirConditionInd'] = NULL;
					$vendoravails[$code_xml]['TransmissionType'] = NULL;
					$vendoravails[$code_xml]['Description'] = strtoupper($vendav->group_Name);
					$vendoravails[$code_xml]['BaggageQuantity'] = NULL;
					$vendoravails[$code_xml]['PassengerQuantity'] = NULL;
					$vendoravails[$code_xml]['Code'] = strtoupper($vendav->SIPP);
					$vendoravails[$code_xml]['BaggageQuantity'] = NULL;
					$vendoravails[$code_xml]['VehicleCategory'] = NULL;
					$vendoravails[$code_xml]['DoorCount'] = NULL;
					$vendoravails[$code_xml]['Size'] = NULL;
					$vendoravails[$code_xml]['Modelo'] = NULL;
					$vendoravails[$code_xml]['RateDistance_Unlimited'] = (string)$vendav->kmsIncluded;
					$vendoravails[$code_xml]['RateDistance_DistUnitName'] = NULL;
					$vendoravails[$code_xml]['RateDistance_VehiclePeriodUnitName'] = NULL;
					$vendoravails[$code_xml]['VehicleCharges'] = (string)$vendav->dayValue;
					$vendoravails[$code_xml]['Rate_RatePeriod'] = NULL;
					$vendoravails[$code_xml]['Rate_RateQualifier'] = trim($vendav->RateID);
					$vendoravails[$code_xml]['Rate_RateCategory'] = NULL;
					$vendoravails[$code_xml]['MinimumDayInd'] = NULL;
					$vendoravails[$code_xml]['MaximumDayInd'] = NULL;
					$vendoravails[$code_xml]['TotalCharge_RateTotalAmount'] = (string)$vendav->valueWithotTax;
					$vendoravails[$code_xml]['TotalCharge_EstimatedTotalAmount'] = (string)$vendav->previewValue;
					$vendoravails[$code_xml]['TotalCharge_CurrencyCode'] = 'EUR';
					$vendoravails[$code_xml]['TotalCharge_DecimalPlaces'] = NULL;
					$vendoravails[$code_xml]['TotalCharge_RateConvertInd'] = NULL;
					$vendoravails[$code_xml]['Fees'] = NULL;
					$vendoravails[$code_xml]['References_ID'] = NULL;
					$vendoravails[$code_xml]['References_Type'] = NULL;
					$vendoravails[$code_xml]['Coverage'] = NULL;
					$vendoravails[$code_xml]['DropCharge'] = NULL;
					$vendoravails[$code_xml]['Availability '] = (string)$vendav->Status_Disponibilidade;
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

    private function parseGroups($received){
        $this->_is_ok = 0;
        if($received->Body->getGroupsResponse->getGroupsResult){
            $this->_is_ok = 1;
            $groups = NULL;
            foreach($received->Body->getGroupsResponse->getGroupsResult->groups->diffgram->Groups->Table as $group){
                $groups[] = array(
                    'id' => (string)trim($group->GroupID),
                    'name'  => (string)trim($group->Group_Name),
                    'SIPP'  => (string)trim($group->SIPP)
                );
            }
            $this->_arr_dados['groups'] = $groups;
        }
    }

    private function parseCities($received){
        $this->_is_ok = 0;
        if($received->Body->getCitiesResponse->getCitiesResult){
            $this->_is_ok = 1;
            $cities = NULL;
            foreach($received->Body->getCitiesResponse->getCitiesResult->cities->diffgram->Countries->Table as $citie){
                $cities[] = array(
                    'city' => (string)trim($citie->city),
                    'country'  => (string)trim($citie->countryID),
                    'ISO'  => (string)trim($citie->ISOCode)
                );
            }
            $this->_arr_dados['cities'] = $cities;
        }
    }

    private function parseCountries($received){
        $this->_is_ok = 0;
        if($received->Body->getCountriesResponse->getCountriesResult){
            $this->_is_ok = 1;
            $countries = NULL;
            foreach($received->Body->getCountriesResponse->getCountriesResult->countries->diffgram->Countries->Table as $country){
                $countries[] = array(
                    'id' => (string)trim($country->countryID),
                    'country'  => (string)trim($country->country),
                    'ISO'  => (string)trim($country->ISOCode)
                );
            }
            $this->_arr_dados['countries'] = $countries;
        }
    }

    private function parseStations($received){
        $this->_is_ok = 0;
        if($received->Body->getStationsResponse->getStationsResult){
            $this->_is_ok = 1;
            $stations = NULL;
            foreach($received->Body->getStationsResponse->getStationsResult->stations->diffgram->RentalStations->Table as $station){
                $stations[] = array(
                    'id' => (string)trim($station->StationID),
                    'name'  => (string)trim($station->Station),
                    'zone'  => (string)trim($station->Zone),
                    'type'  => (string)trim($station->StationType),
                    'country'  => (string)trim($station->CountryID),
                    'city'  => (string)trim($station->City),
                    'latitude'  => (string)trim($station->Latitude),
                    'longitude'  => (string)trim($station->Longitude)
                );
            }
            $this->_arr_dados['stations'] = $stations;
        }
    }

    private function parseStationsDetails($received){
        $this->_is_ok = 0;
        if($received->Body->StationDetailsResponse->StationDetailsResult){
            $this->_is_ok = 1;
            $stations_details = NULL;
            foreach($received->Body->StationDetailsResponse->StationDetailsResult->stationDetails->diffgram->StationDetails->Table as $station_detail){
                $hours = array(
                    'WeekDayOpen' => (string)trim($station_detail->WeekDayOpen),
                    'WeekdayLunchIni' => (string)trim($station_detail->WeekdayLunchIni),
                    'WeekdayLunchFim' => (string)trim($station_detail->WeekdayLunchFim),
                    'WeekDayClose' => (string)trim($station_detail->WeekDayClose),
                    'SaturDayOpen' => (string)trim($station_detail->SaturDayOpen),
                    'SaturdayLunchIni' => (string)trim($station_detail->SaturdayLunchIni),
                    'SaturdayLunchFim' => (string)trim($station_detail->SaturdayLunchFim),
                    'SaturDayClose' => (string)trim($station_detail->SaturDayClose),
                    'SunDayOpen' => (string)trim($station_detail->SunDayOpen),
                    'SundayLunchIni' => (string)trim($station_detail->SundayLunchIni),
                    'SundayLunchFim' => (string)trim($station_detail->SundayLunchFim),
                    'SunDayClose' => (string)trim($station_detail->SunDayClose),
                    'HolidayOpen' => (string)trim($station_detail->HolidayOpen),
                    'HolidayLunchIni' => (string)trim($station_detail->HolidayLunchIni),
                    'HolidayLunchFim' => (string)trim($station_detail->HolidayLunchFim),
                    'HolidayClose' => (string)trim($station_detail->HolidayClose),
                );
                $stations_details[] = array(
                    'id' => (string)trim($station_detail->StationID),
                    'name'  => (string)trim($station_detail->Station),
                    'zone'  => (string)trim($station_detail->Zone),
                    'type'  => (string)trim($station_detail->StationType),
                    'typeDescription'  => (string)trim($station_detail->StationTypeDescription),
                    'address'  => (string)trim($station_detail->Address),

                    'country'  => (string)trim($station_detail->CountryID),
                    'city'  => (string)trim($station_detail->City),
                    'latitude'  => (string)trim($station_detail->Latitude),
                    'longitude'  => (string)trim($station_detail->Longitude)
                );
            }
            $this->_arr_dados['stations_details'] = $stations_details;
        }
    }
}