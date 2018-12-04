<?php

require_once WS_PATH . 'webservice_handler.php';

Final Class WebService_Handler_Gmc extends WebService_Handler {
  
  private $curl_handler;
  
  public function __construct () {
    $this->xml_pesquisar  = WS_PATH . 'gmc_pesquisa.php';
    $this->url_pesquisar  = 'http://tempuri.org/SimpleSearchGroupRental';
    
    $this->xml_reservar   = WS_PATH . 'gmc_reserva.php';
    $this->url_reservar   = 'http://tempuri.org/Reserve';
    
    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
    $this->xml_cancelar   = WS_PATH . '';
    $this->url_cancelar   = '';
    
    $this->xml_visualizar = WS_PATH . '';
    $this->url_visualizar = '';
    
    $this->xml_factory_name = 'gmc';
  }
  
  protected function getURLResponse ($request_data, $url, $url_a = NULL) {
    $this->curl_handler = curl_init("http://www.rdcar.com.br/RDC.WS/Services/PesquisaLegacy.asmx");
    //var_dump($request_data);
	//var_dump($url);
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
                "Content-Type: text/xml;",
                "charset=utf-8",
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