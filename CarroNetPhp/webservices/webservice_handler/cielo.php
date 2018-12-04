<?php

require_once WS_PATH . 'webservice_handler.php';
require_once WS_PATH . 'parser.php';

Final Class WebService_Handler_Cielo extends WebService_Handler {
  
  public function __construct () {
    //$this->xml_pesquisar  = WS_PATH . 'cielo_transacao.php';
      $this->xml_pesquisar  = WS_PATH . 'cielo_transacao_teste.php';
      $this->url_pesquisar  = 'https://qaseCommerce.cielo.com.br/servicos/ecommwsec.do';
      //$this->url_pesquisar  = 'https://ecommerce.cielo.com.br/servicos/ecommwsec.do';
    
      $this->xml_visualizar  = WS_PATH . 'cielo_consulta.php';
      $this->url_visualizar  = 'https://ecommerce.cielo.com.br/servicos/ecommwsec.do';
    
      $this->xml_reservar  = WS_PATH . 'cielo_reserva.php';
      $this->url_reservar  = 'https://ecommerce.cielo.com.br/servicos/ecommwsec.do';
    
      $this->xml_cancelar  = WS_PATH . 'cielo_cancelar.php';
      $this->url_cancelar  = 'https://ecommerce.cielo.com.br/servicos/ecommwsec.do';
    
    $this->xml_factory_name = 'cielo';
  }
  
  protected function getURLResponse ($request_data, $url, $url_a = NULL) {
    
      $this->curl_handler = curl_init($url);
      curl_setopt_array(
          $this->curl_handler,
          array(
              CURLOPT_SSLVERSION => 4,
              CURLOPT_SSL_VERIFYPEER => TRUE,
              CURLOPT_RETURNTRANSFER => TRUE,
              CURLOPT_HTTPHEADER => array("Content-Type: application/x-www-form-urlencoded; charset=utf-8;","Accept: charset=utf-8"),
              CURLOPT_POST => TRUE,
              CURLOPT_POSTFIELDS => 'mensagem='.$request_data
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