<?php

require_once WS_PATH . 'xml_handler.php';

Final Class XML_Handler_Thrifty extends XML_Handler {
  //protected $_a = 'teste';
  
  public function __construct ($received_data, $request_data) {
    $this->_haw_received_data = $received_data;
    $this->_haw_request_data  = $request_data;
	$request  = NULL;
    $received = NULL;
	if (!preg_match('/^-[0-9]*?$/',$received)) {
	    $request  = simplexml_load_string($this->_haw_request_data);
	    $received = simplexml_load_string($this->_haw_received_data);
	}
    if ($received !== NULL) {
		if (isset($received->VehAvailRSCore)) {
		  $this->_type = 'pesquisar';
		  $this->parsePesquisar($request, $received);
		} else if (isset($received->VehResRSCore)) {
		  $this->_type = 'reservar';
		  $this->parseReservar($request, $received);
		} else if ($received->OTA_PayloadStdAttributes["Status"] == "NotProcessed") {
		  $this->_is_ok = 0;
		} else {
		  $this->_is_ok = 2;
		}
	} else {
		$this->_is_ok = 2;
	}
  }
  
  private function parsePesquisar ($request, $received) {
    if (isset($received->Success)) {
      $this->_is_ok = 1;
	  if ($received->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail->VehAvailCore["Status"] == "DISPONIVEL") {
			$this->_is_ok = 1;
		}
		elseif ($received->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail->VehAvailCore["Status"] == "SOB SOLICITACAO") {
			$this->_is_ok = 3;
		}
		elseif ($received->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail->VehAvailCore["Status"] == "INDISPONIVEL") {
			$this->_is_ok = 0;		
		}
		elseif ($received->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehAvails->VehAvail->VehAvailCore["Status"] == "INEXISTENTE") {
			$this->_is_ok = 4;
		}
    } else {
      $this->_is_ok = 0;
    }
  }
  
  private function parseReservar ($request, $received) {
    if (isset($received->Success)) {
      
      $this->_is_ok = 1;
      $this->_xml_id = $received->VehResRSCore->VehReservation->VehSegmentCore->ConfID["ID"];
      $this->_diaria_sem_taxa = $received->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge["Amount"];
	  
      $this->_taxas_diaria = floatval($received->VehResRSCore->VehReservation->VehSegmentCore->Fees->Fee["Amount"]);
	  $this->_taxas_extra = floatval($received->VehResRSCore->VehReservation->VehSegmentCore->RentalRate->VehicleCharges->VehicleCharge->TaxAmounts->TaxAmount[1]["Total"]);
	  $this->_protecao = floatval($received->VehResRSCore->VehReservation->VehSegmentCore->PricedCoverages->PricedCoverage->Charge["Amount"]);
	  $this->_valor_total = floatval($received->VehResRSCore->VehReservation->VehSegmentCore->TotalCharge["EstimatedTotalAmount"]);
      
      
      $o = $received->VehResRSCore->VehReservation->VehSegmentCore;
      for ($i = 0, $s = count($o->PricedEquips); $i < $s; $i++) {
        if ($o->PricedEquips[$i]->PricedEquip->Equipment["EquipType"] == "OWF") {
          $this->_devolucao = array();
          $this->_devolucao["Amount"]     = $o->PricedEquips[$i]->PricedEquip->Charge["Amount"];
          $this->_devolucao["Taxa"]      = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Total"];
          $this->_devolucao["Percentage"] = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Percentage"];
          $this->_devolucao["UnitCharge"] = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitCharge"];
          $this->_devolucao["UnitName"]   = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitName"];
        } else if ($o->PricedEquips[$i]->PricedEquip->Equipment["EquipType"] == "8") {
          $this->_cadeira_bebe = array();
          $this->_cadeira_bebe["Amount"]     = $o->PricedEquips[$i]->PricedEquip->Charge["Amount"];
          $this->_cadeira_bebe["Total"]      = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Total"];
          $this->_cadeira_bebe["Percentage"] = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Percentage"];
          $this->_cadeira_bebe["UnitCharge"] = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitCharge"];
          $this->_cadeira_bebe["UnitName"]   = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitName"];
        } else if ($o->PricedEquips[$i]->PricedEquip->Equipment["EquipType"] == "13") {
          $this->_gps = array();
          $this->_gps["Amount"]     = $o->PricedEquips[$i]->PricedEquip->Charge["Amount"];
          $this->_gps["Total"]      = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Total"];
          $this->_gps["Percentage"] = $o->PricedEquips[$i]->PricedEquip->Charge->TaxAmounts->TaxAmount["Percentage"];
          $this->_gps["UnitCharge"] = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitCharge"];
          $this->_gps["UnitName"]   = $o->PricedEquips[$i]->PricedEquip->Charge->Calculation["UnitName"];
        }
      }
    } else {
      $this->_is_ok = 0;
    }
  }
}

?>