<?php
require_once WS_PATH . 'webservice_handler.php';
require_once WS_PATH . 'parser.php';

Final Class WebService_Handler_Fleetmax extends WebService_Handler {
  
  public function __construct () {
    $this->xml_pesquisar  = WS_PATH . 'fleetmax_pesquisa.php';
    $this->url_pesquisar  = 'http://carroaluguel.ddns.com.br:8083/soap/IWbsTransfXML';
    
    $this->xml_reservar   = WS_PATH . 'fleetmax_reserva.php';
    $this->url_reservar   = 'http://carroaluguel.ddns.com.br:8083/soap/IWbsTransfXML';
    
    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
	$this->xml_cancelar   = WS_PATH . 'fleetmax_cancelar.php';
    $this->url_cancelar   = 'http://carroaluguel.ddns.com.br:8083/soap/IWbsTransfXML';


    $this->xml_visualizar = WS_PATH . 'fleetmax_visualizar.php';
    $this->url_visualizar = 'http://carroaluguel.ddns.com.br:8083/soap/IWbsTransfXML';
    
    $this->xml_factory_name = 'fleetmax';
  }
    
    
    
    protected function getURLResponse ($request_data, $url, $url_a = NULL) {
        //$this->curl_handler = curl_init("http://reservas.ddns.com.br:8081/soap/IWbsTransfXML");
        $this->curl_handler = curl_init("http://carroaluguel.ddns.com.br:8083/soap/IWbsTransfXML");
        $opts = array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_HTTPPROXYTUNNEL => TRUE,
            CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/xml;",
                "charset=utf-8",
            ),
            CURLOPT_SSL_VERIFYPEER => FALSE,
        );
        if($url == $this->url_pesquisar){
            $opts[CURLOPT_CONNECTTIMEOUT] = 30;
            $opts[CURLOPT_TIMEOUT_MS] = 15000;
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