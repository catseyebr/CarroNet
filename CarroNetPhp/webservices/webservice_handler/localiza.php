<?php

require_once WS_PATH . 'webservice_handler.php';

Final Class WebService_Handler_Localiza extends WebService_Handler {

	private $curl_handler;
  
  public function __construct () {
    $this->xml_pesquisar  = WS_PATH . 'localiza_pesquisa.php';
    $this->url_pesquisar  = 'http://www.opentravel.org/OTA/2003/05:OTA_VehAvailRateRQ';

    $this->xml_reservar   = WS_PATH . 'localiza_reserva.php';
    $this->url_reservar  = 'http://www.opentravel.org/OTA/2003/05:OTA_VehResRQ';

    $this->xml_alterar    = WS_PATH . '';
    $this->url_alterar    = '';
    
	$this->xml_cancelar   = WS_PATH . 'localiza_cancelar.php';
    $this->url_cancelar   = 'http://www.opentravel.org/OTA/2003/05:OTA_VehCancelRQ';
    
    $this->xml_visualizar = WS_PATH . 'localiza_visualizar.php';
    $this->url_visualizar = 'http://www.opentravel.org/OTA/2003/05:OTA_VehRetResRQ';

    $this->xml_lojas   = WS_PATH . 'localiza_lojas.php';
    $this->url_lojas   = 'http://www.opentravel.org/OTA/2003/05:OTA_VehLocDetailRQ';

	$this->xml_lojas_pesquisa   = WS_PATH . 'localiza_lojas_pesquisa.php';
	$this->url_lojas_pesquisa   = 'http://www.opentravel.org/OTA/2003/05:OTA_VehLocSearchRQ';
    
    $this->xml_factory_name = 'localiza';
  }

	protected function getURLResponse ($request_data, $url, $url_a = NULL) {
		$access = 'https://nr.localiza.com/localiza/nucleoreserva/reserva/OTA2013A.svc';

		$this->curl_handler = curl_init($access);
		$opts = array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_HTTPPROXYTUNNEL => TRUE,
            CURLOPT_POSTFIELDS => $request_data,
            CURLINFO_HEADER_OUT => TRUE,
            //CURLOPT_USERPWD => 'svc-nrcalhom:DRS1TDVv5NU',
            CURLOPT_USERPWD => 'svc-nrcalprd:DRS1TDVv5NU',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/xml; charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                'SOAPAction: "' . $url . '"',
                "Content-length: ".strlen($request_data),
            ),
            CURLOPT_SSL_VERIFYPEER => FALSE
        );
        if($url == $this->url_pesquisar){
            $opts[CURLOPT_CONNECTTIMEOUT] = 30;
            $opts[CURLOPT_TIMEOUT_MS] = 300000;
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