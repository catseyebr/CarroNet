<?php

require_once WS_PATH . 'webservice_handler.php';

Final Class WebService_Handler_Yes extends WebService_Handler {
  
  private $curl_handler;
  
  public function __construct () {
    $this->xml_pesquisar  = WS_PATH . 'yes_pesquisa.php';
    $this->url_pesquisar  = "http://locaviayes.dyndns.org:8090/datasnap/rest/TServerMetodos/PesquisarDisponibilidade";
    
    $this->xml_reservar   = WS_PATH . 'yes_reserva.php';
    $this->url_reservar   = 'http://locaviayes.dyndns.org:8090/datasnap/rest/TServerMetodos/ConfirmarReserva';
    
    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
    $this->xml_cancelar   = WS_PATH . 'yes_cancelar.php';
    $this->url_cancelar   = 'http://locaviayes.dyndns.org:8090/datasnap/rest/TServerMetodos/CancelarReserva';
    
    $this->xml_visualizar = WS_PATH . 'yes_visualizar.php';
    $this->url_visualizar = 'http://locaviayes.dyndns.org:8090/datasnap/rest/TServerMetodos/ConsultarReserva';
    
    $this->xml_factory_name = 'yes';
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
                CURLOPT_CONNECTTIMEOUT => 1,
                CURLOPT_TIMEOUT_MS => 5000,
                CURLOPT_POSTFIELDS => $request_data,
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