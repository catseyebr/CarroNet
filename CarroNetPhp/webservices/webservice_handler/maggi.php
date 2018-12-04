<?php
require_once WS_PATH . 'webservice_handler.php';
require_once WS_PATH . 'parser.php';

Final Class WebService_Handler_Maggi extends WebService_Handler {
  
  public function __construct () {
    $this->xml_pesquisar  = WS_PATH . 'maggi_pesquisa.php';
    $this->url_pesquisar  = 'http://sistemas.empresasmaggi.com.br:81/fleet/WebService/wsReservaXmlCarroAluguel.asmx';
    
    $this->xml_reservar   = WS_PATH . 'maggi_reserva.php';
    $this->url_reservar   = 'http://sistemas.empresasmaggi.com.br:81/fleet/WebService/wsReservaXmlCarroAluguel.asmx';
    
    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
	$this->xml_cancelar   = WS_PATH . 'maggi_cancelar.php';
    $this->url_cancelar   = 'http://sistemas.empresasmaggi.com.br:81/fleet/WebService/wsReservaXmlCarroAluguel.asmx';


    $this->xml_visualizar = WS_PATH . 'maggi_visualizar.php';
    $this->url_visualizar = 'http://sistemas.empresasmaggi.com.br:81/fleet/WebService/wsReservaXmlCarroAluguel.asmx';

      $this->xml_lojas   = WS_PATH . 'maggi_lojas.php';
      $this->url_lojas   = 'http://sistemas.empresasmaggi.com.br:81/fleet/WebService/wsReservaXmlCarroAluguel.asmx';
    
    $this->xml_factory_name = 'maggi';
  }
    
    
    
    protected function getURLResponse ($request_data, $url, $url_a = NULL) {
        $this->curl_handler = curl_init("http://sistemas.empresasmaggi.com.br:81/fleet/WebService/wsReservaXmlCarroAluguel.asmx");
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
            $opts[CURLOPT_CONNECTTIMEOUT] = 1;
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