<?php

require_once WS_PATH . 'xml_handler.php';

Final Class XML_Handler_Gmc extends XML_Handler {
	
  
  public function __construct ($received_data, $request_data) {
  
    $this->_haw_received_data = $received_data;
    $this->_haw_request_data  = $request_data;
    
    /**
     * Troca padrão de codificação SOAP para padrão de codificação XML aceitável em simplexml_load_string(); 
     */         
    $data = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>",$this->_haw_received_data); 
    $data = preg_replace('/<\?xml.*?\?>/', "",$data); //<?php
    $received = simplexml_load_string($data);
	
	$data2 = preg_replace('/<(\/|)soap:(Envelope|Body|Fault).*?>/', "<$1$2>",$this->_haw_request_data); 
    $data2 = preg_replace('/<\?xml.*?\?>/', "",$data2); //<?php
    $requested = simplexml_load_string(html_entity_decode($data2, ENT_COMPAT, "UTF-8"));
	
    //var_dump($requested);
    if (isset($received->Body->SimpleSearchGroupRentalResponse->SimpleSearchGroupRentalResult->OTA_VehAvailRateRS)) {
      $this->_type = 'pesquisar';
      $this->parsePesquisar($requested, $received);
    } else if ($received->Body->ReserveResponse->ReserveResult->OTA_VehResRS) {
      $this->_type = 'reservar';
      $this->parseReservar($request, $received);
    } else {
      $this->_is_ok = 2;
    }
  }
  
  private function parsePesquisar ($requested, $received) {
    $this->_is_ok = 0;
	if (isset($received->Body->SimpleSearchGroupRentalResponse->SimpleSearchGroupRentalResult->OTA_VehAvailRateRS->Success)) {
		$p = $received->Body->SimpleSearchGroupRentalResponse->SimpleSearchGroupRentalResult->OTA_VehAvailRateRS->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail;
		if ($p->VehAvailCore["Status"] == "AVAILABLE"){
			$this->_is_ok = 1;
			//var_dump($p);
		}
		//velho método
		/*
		$p = $received->Body->SimpleSearchGroupRentalResponse->SimpleSearchGroupRentalResult->OTA_VehAvailRateRS->VehAvailRSCore;
		for ($j = 0, $r = count($p); $j < $r; $j++) {
			if($p[$j]->VehVendorAvails->VehVendorAvail->Vendor['Code']==(integer)$requested->Body->SimpleSearchGroupRental->rentalsCodes){
				$o = $p[$j]->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail;
				for ($i = 0, $s = count($o); $i < $s; $i++) {
					if ($o[$i]->VehAvailCore["Status"] == "AVAILABLE" /*&& $o[$i]->VehAvailCore->Vehicle->VehType["VehicleCategory"] == (integer)$requested->Body->SimpleSearch->tipo && $o[$i]->VehAvailCore->Vehicle->VehClass["Size"] == (integer)$requested->Body->SimpleSearch->size) {
					
					$this->_is_ok = 1;
					var_dump($o[$i]);
				}
			}
		 }
		
	  }
	  */
    }
	
  }
  
  private function parseReservar ($request, $received) {
	  if ($received->Body->ReserveResponse->ReserveResult->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"] != "") {
      
      $this->_is_ok = 1;
      $this->_xml_id = $received->Body->ReserveResponse->ReserveResult->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
	  
      $this->_diaria_sem_taxa = $received->Body->ReserveResponse->ReserveResult->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge["Amount"];
	  $this->_protecao = $received->Body->ReserveResponse->ReserveResult->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->PricedCoverages->PricedCoverage->Charge['Amount'];
      
	  $o = $received->Body->ReserveResponse->ReserveResult->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->TaxAmounts;
      for ($i = 0, $s = count($o->TaxAmount); $i < $s; $i++) {
		  	if($o->TaxAmount[$i]["Description"]=="Hour"){
				$hora_extra = $hora_extra +	floatval($o->TaxAmount[$i]["Total"]);
			}else{
    			$total_taxas = $total_taxas +  floatval($o->TaxAmount[$i]["Total"]);
			}
	  }
	  $taxas_diaria = $total_taxas * $received->Body->ReserveResponse->ReserveResult->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->Calculation["Quantity"];
	  $valor_diarias_total = $this->_diaria_sem_taxa * $received->Body->ReserveResponse->ReserveResult->OTA_VehResRS->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->Calculation["Quantity"];
	  $this->_taxas_diaria = $taxas_diaria + $hora_extra;
	  $this->_valor_total = floatval($valor_diarias_total) + floatval($this->_taxas_diaria);
    } else {
      $this->_is_ok = 0;
    }
  }
}

?>