<?php

require_once WS_PATH . 'webservice_handler.php';

Final Class WebService_Handler_Holiday extends WebService_Handler {
  
  private $curl_handler;
  
  public function __construct () {
    $this->xml_locations  = WS_PATH . 'holiday_pesquisa.php';
    $this->url_locations  = "http://tempuri.org/MTServiceRequestString";
	
    $this->xml_offices  = WS_PATH . 'holiday_pesquisa.php';
    $this->url_offices  = "http://tempuri.org/MTServiceRequestString";
	
    $this->xml_pesquisar  = WS_PATH . 'holiday_pesquisa.php';
    $this->url_pesquisar  = "http://tempuri.org/MTServiceRequestString";
    
    $this->xml_reservar   = WS_PATH . ''; 
    $this->url_reservar   = '';
    
    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
    $this->xml_cancelar   = WS_PATH . '';
    $this->url_cancelar   = '';
    
    $this->xml_visualizar = WS_PATH . '';
    $this->url_visualizar = '';
    
    $this->xml_factory_name = 'holiday';
  }

  public function locations () {
    return $this->returnResponse($this->xml_locations, $this->url_locations);
  }

  public function offices () {
    return $this->returnResponse($this->xml_offices, $this->url_offices);
  }
  
  protected function getURLResponse ($request_data, $url, $url_a = NULL) {
    $this->curl_handler = curl_init("http://test.webservices.tp.maintrackservices.com/wsmtrentalcargatewayv10/wsmtrentalcargateway.asmx");
    
    curl_setopt_array(
        $this->curl_handler,
        array(
            CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_HTTPPROXYTUNNEL => TRUE,
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/xml; charset=utf-8",
                'SOAPAction: "' . $url . '"',
            ),
            CURLOPT_SSL_VERIFYPEER => FALSE, 
        )
    );  
    
    return  $this->getURL();
  }
  
  private function getURL($recDepth = NULL) {
    $recDepth = ($recDepth > 1) ? $recDepth : 1;
    try {
      $result = curl_exec($this->curl_handler);
	  //var_dump(curl_getinfo($this->curl_handler));
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