<?php
    require_once WS_PATH . 'webservice_handler.php';
    
    Final Class WebService_Handler_Foco extends WebService_Handler
    {
        
        private $curl_handler;
        
        public function __construct()
        {
            $this->xml_pesquisar = WS_PATH . 'foco_pesquisa.php';
            //$this->url_pesquisar  = "https://www.thermeon.com/webXG/foco/xml/webxml";
            $this->url_pesquisar  = "https://webxml.eu.thermeon.io/webXG/foco/xml/webxml";
            $this->xml_reservar = WS_PATH . 'foco_reserva.php';
            $this->url_reservar = 'https://webxml.eu.thermeon.io/webXG/foco/xml/webxml';
            $this->xml_alterar = WS_PATH . '';
            $this->url_alterar = '';
            $this->xml_cancelar = WS_PATH . 'foco_cancelar.php';
            $this->url_cancelar = 'https://webxml.eu.thermeon.io/webXG/foco/xml/webxml';
            $this->xml_visualizar = WS_PATH . 'foco_visualizar.php';
            $this->url_visualizar = 'https://webxml.eu.thermeon.io/webXG/foco/xml/webxml';
            $this->xml_factory_name = 'foco';
        }
        
        protected function getURLResponse($request_data, $url, $url_a = NULL)
        {
            $this->curl_handler = curl_init("https://webxml.eu.thermeon.io/webXG/foco/xml/webxml");
            curl_setopt_array($this->curl_handler, array(
                    CURLOPT_POST            => true,
                    CURLOPT_RETURNTRANSFER  => true,
                    CURLOPT_FOLLOWLOCATION  => true,
                    CURLOPT_HTTPPROXYTUNNEL => true,
                    CURLOPT_CONNECTTIMEOUT  => 20,
                    CURLOPT_TIMEOUT_MS      => 20000,
                    CURLOPT_POSTFIELDS      => $request_data,
                    CURLINFO_HEADER_OUT     => true,
                    CURLOPT_USERPWD         => 'layum:14HR@!7S',
                    CURLOPT_HTTPHEADER      => array(
                        "Content-Type: text/xml; charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        'SOAPAction: "' . $url . '"',
                        "Content-length: " . strlen($request_data),
                    ),
                    CURLOPT_SSL_VERIFYPEER  => 0,
                    CURLOPT_SSL_VERIFYHOST => 0
                ));
            $result = curl_exec($this->curl_handler);
            return $this->getURL($result);
        }
        
        private function getURL($result = null)
        {
            return $result;
        }
    }