<?php

require_once WS_PATH . 'webservice_handler.php';

Final Class WebService_Handler_Seguroviage extends WebService_Handler {
  
  private $curl_handler;
  
  public function __construct () {
    $this->xml_cotacao  = WS_PATH . 'seguroviage_cotar.php';
    $this->url_cotacao  = "http://181.48.14.77/sides/Traveller_WS/wsemision.asmx";
    
    $this->xml_location  = WS_PATH . 'seguroviage_location.php';
    $this->url_location  = "http://181.48.14.77/sides/Traveller_WS/wsemision.asmx";
    
    $this->xml_reservar   = WS_PATH . '';
    $this->url_reservar   = '';
    
    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
    $this->xml_cancelar   = WS_PATH . '';
    $this->url_cancelar   = '';
    
    $this->xml_visualizar = WS_PATH . '';
    $this->url_visualizar = '';
    
    $this->xml_factory_name = 'seguroviage';
  }
  
  	public function cotar() {
  		return $this->returnResponse($this->xml_cotacao, $this->url_cotacao);
  	}

	public function localidade() {
		return $this->returnResponse($this->xml_location, $this->url_location);
  	}

 protected function getURLResponse ($request_data, $url, $url_a = NULL) {
    $this->curl_handler = curl_init($url);
    curl_setopt_array(
        $this->curl_handler,
        array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_HTTPPROXYTUNNEL => TRUE,
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_HTTPHEADER => array("Content-Type: text/xml;", "charset=utf-8"),
            CURLOPT_SSL_VERIFYPEER => FALSE,
        )
    );  
    
    return  $this->getURL();
  }
  
  private function getURL($recDepth = NULL) {
    $recDepth = ($recDepth > 1) ? $recDepth : 1;
    try {
      $result = curl_exec($this->curl_handler);
	  if (!$result) {
        throw new Exception('---- ERRO!!!! -----');
      }
    } catch (Exception $e) {
      if ($recDepth <= 3) {
        $result = $this->getURL($recDepth+1);
      }
    }
    
    return $result;
  }
}