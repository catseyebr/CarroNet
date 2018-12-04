<?php

require_once WS_PATH . 'webservice_handler.php';
require_once WS_PATH . 'parser.php';

Final Class WebService_Handler_Thrifty extends WebService_Handler {
  
  public function __construct () {
    $this->xml_pesquisar  = WS_PATH . 'thrifty_pesquisa.php';
    //$this->url_pesquisar  = 'http://187.45.229.49/etools/erental/thriftynew/ota_vehavaillrate.asp';
	$this->url_pesquisar  = 'http://186.202.117.202/etools/erental/thriftynew/ota_vehavaillrate.asp';
	
    
    $this->xml_reservar   = WS_PATH . 'thrifty_reserva.php'; 
   // $this->url_reservar   = 'http://187.45.229.49/etools/erental/thriftynew/ota_vehres.asp';
	$this->url_reservar   = 'http://186.202.117.202/etools/erental/thriftynew/ota_vehres.asp';
    
    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
    $this->xml_cancelar   = WS_PATH . '';
    $this->url_cancelar   = '';
    
    $this->xml_vasualizar = WS_PATH . '';
    $this->url_vasualizar = '';
    
    $this->xml_factory_name = 'thrifty';
  }
  
  protected function getURLResponse ($request_data, $url, $url_a = NULL) {
	return  $this->getURL($url . "?xmlData=" . htmlentities($request_data));
	}
  
  private function getURL($url, $recDepth = NULL) {
	$recDepth = ($recDepth > 1) ? $recDepth : 1;
    try {
	$result = file_get_contents($url);
      if (!$result) {
        throw new Exception('---- ERRO!!!! -----');
      }
    } catch (Exception $e) {
      if ($recDepth <= 3) {
        $result = $this->getURL($url, $recDepth+1);
      }
    }
    return $result;
  }
}