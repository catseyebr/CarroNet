<?php

require_once WS_PATH . 'webservice_handler.php';

Final Class WebService_Handler_Cmnet extends WebService_Handler {
  
  private $curl_handler;
  
  public function __construct () {
    $this->xml_hoteis  = WS_PATH . 'cmnet_pesquisa_hoteis.php';
    $this->url_hoteis  = "http://www.cmnet/xmlwebservices2/xmlConsultaHoteis";
    
    $this->xml_detalheshot   = WS_PATH . 'cmnet_detalhes_hoteis.php'; 
    $this->url_detalheshot   = 'http://www.cmnet/xmlwebservices2/xmlRetornaInfoHotel';
	
	$this->xml_pesquisar  = WS_PATH . '';
    $this->url_pesquisar  = "";
    
    $this->xml_reservar   = WS_PATH . '';
    $this->url_reservar   = '';
    
    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
    $this->xml_cancelar   = WS_PATH . '';
    $this->url_cancelar   = '';
    
    $this->xml_visualizar = WS_PATH . '';
    $this->url_visualizar = '';
    
    $this->xml_factory_name = 'cmnet';
  }
  
  	public function hoteis() {
  		return $this->returnResponse($this->xml_hoteis, $this->url_hoteis);
  	}

	public function detalhesHoteis() {
		return $this->returnResponse($this->xml_detalheshot, $this->url_detalheshot);
  	}

 protected function getURLResponse ($request_data, $url, $url_a = NULL) {
    $this->curl_handler = curl_init("https://webservices3.cmnet.com.br/v4/ota/reservas/OTA_Reservas.asmx");
    
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