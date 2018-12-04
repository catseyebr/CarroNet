<?php

require_once WS_PATH . 'xml_handler.php';

Final Class XML_Handler_Holiday extends XML_Handler {
  
  public $locations;
  
  public function __construct ($received_data, $request_data) {
  
    $this->_haw_received_data = $received_data;
    $this->_haw_request_data  = $request_data;
    
    $data = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>",$this->_haw_received_data);
    $data = preg_replace('/<\?xml.*?\?>/', "",$data);
    
    $received = simplexml_load_string($data);
	$stringData = simplexml_load_string($received->Body->MTServiceRequestStringResponse->MTServiceRequestStringResult);
	if (is_object($stringData)) {
		if (is_object($stringData->responseHeader)) {
			if ($stringData->responseHeader->requestType == "searchLocations") {
			  $this->_type = 'locations';
			  $this->parseLocations($stringData);
			} /* else if ($stringData->responseHeader->requestType== "searchRentalCar") {
			  $this->_type = 'reservar';
			  $this->parseReservar($request, $received);
			} */ else {
			  $this->_is_ok = 2;
			}
		} else {
			$this->_is_ok = 2;
		}
	} else {
			$this->_is_ok = 2;
	}
  }
  
  private function parseLocations ($stringData) {
    $this->_is_ok = 0;
	
    if ($stringData->responseHeader->resultOk== "Y") {
         $this->_is_ok = 1;
		 $o = $stringData->responseBody;
		 $this->locations = $o;
	}
  }
  
  private function parseReservar ($request, $received) {
	  if ($received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"] != "") {
      
      $this->_is_ok = 1;
      $this->_xml_id = $received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
	  
      $this->_diaria_sem_taxa = $received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->Calculation["UnitCharge"];
      
	  $o = $received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentCore->Fees;
      for ($i = 0, $s = count($o->Fee); $i < $s; $i++) {
    		$total_taxas = $total_taxas +  floatval($o->Fee["Amount"]);
	  }
	  $this->_taxas_diaria = $total_taxas / $received->Body->OtaVehResResponse->OtaVehResResult->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->Calculation["Quantity"];
    } else {
      $this->_is_ok = 0;
    }
  }
}

?>