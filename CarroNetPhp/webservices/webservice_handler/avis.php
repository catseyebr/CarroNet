<?php

require_once WS_PATH . 'webservice_handler.php';

Final Class WebService_Handler_Avis extends WebService_Handler {
  
  private $curl_handler;
  
  public function __construct () {
    $this->xml_pesquisar  = WS_PATH . 'avis_disponibilidade.php';
    $this->url_pesquisar  = "https://services.carrental.com/wsbang/HTTPSOAPRouter/ws9071";
    
    $this->xml_reservar   = WS_PATH . 'avis_reserva.php'; 
    $this->url_reservar   = 'https://services.carrental.com/wsbang/HTTPSOAPRouter/ws9071';
    
    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
    $this->xml_cancelar   = WS_PATH . 'avis_cancelar.php';
    $this->url_cancelar   = 'https://services.carrental.com/wsbang/HTTPSOAPRouter/ws9071';
    
    $this->xml_visualizar = WS_PATH . 'avis_visualizar.php';
    $this->url_visualizar = 'https://services.carrental.com/wsbang/HTTPSOAPRouter/ws9071';

    $this->xml_lojas   = WS_PATH . 'avis_lojas.php';
    $this->url_lojas   = 'https://services.carrental.com/wsbang/HTTPSOAPRouter/ws9071';
    
    $this->xml_factory_name = 'avis';
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
            CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_TIMEOUT_MS => 30000,
            CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_HTTPHEADER => array("Content-Type: application/soap+xml;", "charset=utf-8"),
            CURLOPT_SSL_VERIFYPEER => FALSE, 
        )
    );
      $result = curl_exec($this->curl_handler);
    return  $this->getURL($result);
  }
  
  private function getURL($result = NULL) {
    return $result;
  }
}