<?php

require_once WS_PATH . 'webservice_handler.php';

Final Class WebService_Handler_Rdcars extends WebService_Handler {
  
  private $curl_handler;
  
  public function __construct () {
    $this->xml_pesquisar  = WS_PATH . 'rdcars_pesquisa.php';
    $this->url_pesquisar  = 'http://tempuri.org/RentalSearchEquipTypes';
    
    $this->xml_reservar   = WS_PATH . 'rdcars_reserva.php';
    $this->url_reservar   = 'http://tempuri.org/Reserve2';
    
    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
    $this->xml_cancelar   = WS_PATH . 'rdcars_cancelar.php';
    $this->url_cancelar   = 'http://tempuri.org/CancelReserve';
    
    $this->xml_visualizar = WS_PATH . 'rdcars_visualizar.php';
    $this->url_visualizar = 'http://tempuri.org/ViewReserveAutoCurrency2';

    $this->xml_pesquisateste = WS_PATH . 'rdcars_pesquisateste.php';
    $this->url_pesquisateste = 'http://tempuri.org/Write';
    
    $this->xml_factory_name = 'rdcars';
  }
  
  protected function getURLResponse ($request_data, $url, $url_a = NULL) {
    $this->curl_handler = curl_init("http://services.rdcar.com.br/PesquisaLegacy.asmx");
    //$this->curl_handler = curl_init("http://beta.rdcar.com.br/PesquisaService.asmx");
    //var_dump($request_data);
	//var_dump($url);
      $opts = array(
          CURLOPT_POST => TRUE,
          CURLOPT_RETURNTRANSFER => TRUE,
          CURLOPT_FOLLOWLOCATION => TRUE,
          CURLOPT_HTTPPROXYTUNNEL => TRUE,
          CURLOPT_POSTFIELDS => $request_data,
          CURLOPT_HTTPHEADER => array(
              "Content-Type: text/xml;",
              "charset=UTF-8",
              'SOAPAction: "' . $url . '"',
          ),
          CURLOPT_SSL_VERIFYPEER => FALSE,
      );
      if($url == $this->url_pesquisar){
          $opts[CURLOPT_CONNECTTIMEOUT] = 30;
          $opts[CURLOPT_TIMEOUT_MS] = 30000;
      }
      curl_setopt_array(
        
          $this->curl_handler,
          $opts
      );
      $result = curl_exec($this->curl_handler);
    return  $this->getURL($result);
  }
   
  private function getURL($result = NULL) {
    return $result;
  }
}