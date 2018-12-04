<?php
require_once WS_PATH . 'xml_handler.php';
Final Class XML_Handler_Seguroviage extends XML_Handler {
  
    public function __construct ($received_data, $request_data) {
        $this->_haw_received_data = $received_data;
        $this->_haw_request_data  = $request_data;
	    /**
        * Troca padrão de codificação SOAP para padrão de codificação XML aceitável em simplexml_load_string();
        */
        $data = preg_replace('/<(\/|)SOAP-ENV:(Envelope|Body|Fault).*?>/', "<$1$2>",$this->_haw_received_data); //<?php
        $data = preg_replace('/<(\/|)ns:(.*?)>/', "<$1$2>",$data); //<?php
        $received = simplexml_load_string($data);
    if (isset($received->Body->ListarOrigemDestinoResponse)) {
      $this->_type = 'listardestino';
      $this->parseListarDestino($received);
    } else if ($received->Body->CotarResponse) {
      $this->_type = 'cotar';
      $this->parseCotar($received);
	} else {
      $this->_is_ok = 2;
    }
  }
  
    private function parseListarDestino ($received) {
        $this->_is_ok = 0;
        if (isset($received->Body->ListarOrigemDestinoResponse->ListarOrigemDestinoResult)) {
            $this->_is_ok = 1;
        }
    }
  
    private function parseCotar ($received) {
        $this->_is_ok = 0;
        if (isset($received->Body->CotarResponse->CotarResult->ID)) {

            $this->_is_ok = 1;
        }
    }
}
?>