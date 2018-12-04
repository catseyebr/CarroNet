<?php

require_once WS_PATH . 'webservice_handler.php';

Final Class WebService_Handler_Unidas extends WebService_Handler {
  
  private $curl_handler;
  
  public function __construct () {
    $this->xml_pesquisar  = WS_PATH . 'unidas_pesquisa.php';
    $this->url_pesquisar  = "http://www.unidas.com.br/OtaVehAvailRate";
    
    $this->xml_reservar   = WS_PATH . 'unidas_reserva.php'; 
    $this->url_reservar   = 'http://www.unidas.com.br/OtaVehRes';
    
    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
    $this->xml_cancelar   = WS_PATH . 'unidas_cancelar.php';
    $this->url_cancelar   = 'http://www.unidas.com.br/OtaVehCancel';
    
    $this->xml_visualizar = WS_PATH . 'unidas_visualizar.php';
    $this->url_visualizar = 'http://www.unidas.com.br/OtaVehRetRes';

    $this->xml_lojas   = WS_PATH . 'unidas_lojas_pesquisa.php';
    $this->url_lojas   = 'http://www.unidas.com.br/OtaVehLocSearch';
    
    $this->xml_factory_name = 'unidas';
  }
  
  protected function getURLResponse ($request_data, $url, $url_a = NULL) {
    $this->curl_handler = curl_init("https://wbs.unidas.com.br/WBS2Z_OTA.asmx");
      $opts = array(
          CURLOPT_POST => TRUE,
          CURLOPT_RETURNTRANSFER => TRUE,
          CURLOPT_FOLLOWLOCATION => TRUE,
          CURLOPT_HTTPPROXYTUNNEL => TRUE,
          CURLOPT_POSTFIELDS => $request_data,
          CURLOPT_HTTPHEADER => array(
              "Content-Type: text/xml;",
              "charset=utf-8",
              'SOAPAction: "' . $url . '"',
          ),
          CURLOPT_SSL_VERIFYPEER  => 0,
          CURLOPT_SSL_VERIFYHOST => 0
      );
      if($url == $this->url_pesquisar){
          $opts[CURLOPT_CONNECTTIMEOUT] = 30;
          $opts[CURLOPT_TIMEOUT_MS] = 30000;
      }else{
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