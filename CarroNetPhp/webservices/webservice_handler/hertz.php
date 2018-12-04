<?php
    require_once WS_PATH . 'webservice_handler.php';
    
    Final Class WebService_Handler_Hertz extends WebService_Handler
    {
        
        private $curl_handler;
        
        public function __construct()
        {
            $this->xml_pesquisar = WS_PATH . 'hertz_pesquisa.php';
            $this->url_pesquisar = "https://vv.xnet.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a";
            $this->xml_reservar = WS_PATH . 'hertz_reserva.php';
            $this->url_reservar = 'https://vv.xnet.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a';
            $this->xml_alterar = WS_PATH . 'hertz_alterar.php';
            $this->url_alterar = 'https://vv.xnet.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a';
            $this->xml_cancelar = WS_PATH . 'hertz_cancelar.php';
            $this->url_cancelar = 'https://vv.xnet.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a';
            $this->xml_visualizar = WS_PATH . 'hertz_visualizar.php';
            $this->url_visualizar = 'https://vv.xnet.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a';
            $this->xml_lojas = WS_PATH . 'hertz_lojas_pesquisa.php';
            $this->url_lojas = 'https://vv.xnet.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a';
            $this->xml_factory_name = 'hertz';
        }
        
        protected function getURLResponse($request_data, $url, $url_a = NULL)
        {
            $this->curl_handler = curl_init("https://vv.xnet.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a");
            $opts = array(
                CURLOPT_POST            => true,
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_FOLLOWLOCATION  => true,
                CURLOPT_HTTPPROXYTUNNEL => true,
                CURLOPT_POSTFIELDS      => $request_data,
                CURLOPT_HTTPHEADER      => array(
                    "Content-Type: text/xml;",
                    "charset=utf-8",
                    'SOAPAction: "' . $url . '"',
                ),
                CURLOPT_SSL_VERIFYPEER  => false,
            );
            if ($url == $this->url_pesquisar) {
                $opts[CURLOPT_CONNECTTIMEOUT] = 1;
                $opts[CURLOPT_TIMEOUT_MS] = 5000;
            }
            curl_setopt_array($this->curl_handler, $opts);
            $result = curl_exec($this->curl_handler);
            return $this->getURL($result);
        }
        
        private function getURL($result = null)
        {
            return $result;
        }
    }