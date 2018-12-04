<?php
require_once WS_PATH . 'webservice_handler.php';
require_once WS_PATH . 'parser.php';

Final Class WebService_Handler_Movida extends WebService_Handler
{

    public function __construct()
    {
        $this->xml_pesquisar = WS_PATH . 'movida_pesquisa.php';
        //$this->url_pesquisar  = 'http://vetor.movida.com.br/movida/ws2/wsoap.php';
        //$this->url_pesquisar  = 'https://177.154.152.138/movida/ws3/';
        //$this->url_pesquisar = 'https://xml.movida.com.br/movida/ws3/index.php';
        //$this->url_pesquisar  = 'https://gru.movida.com.br/movida/ws3/index.php';
        $this->url_pesquisar = 'https://ws3.movida.com.br/ws3/index.php';

        $this->xml_reservar = WS_PATH . 'movida_reserva.php';
        //$this->url_reservar  = 'http://vetor.movida.com.br/movida/ws2/wsoap.php';
        //$this->url_reservar = 'https://xml.movida.com.br/movida/ws3/index.php';
        $this->url_reservar = 'https://ws3.movida.com.br/ws3/index.php';

        $this->xml_alterar = WS_PATH . '';
        $this->url_alterar = '';

        $this->xml_cancelar = WS_PATH . 'movida_cancelar.php';
        //$this->url_cancelar   = 'http://vetor.movida.com.br/movida/ws2/wsoap.php';
        //$this->url_cancelar = 'https://xml.movida.com.br/movida/ws3/index.php';
        $this->url_cancelar = 'https://ws3.movida.com.br/ws3/index.php';


        $this->xml_visualizar = WS_PATH . 'movida_visualizar.php';
        //$this->url_visualizar = 'http://vetor.movida.com.br/movida/ws2/wsoap.php';
        //$this->url_visualizar = 'https://xml.movida.com.br/movida/ws3/index.php';
        $this->url_visualizar = 'https://ws3.movida.com.br/ws3/index.php';

        $this->xml_pesquisateste = WS_PATH . 'movida_pesquisa.php';
        //$this->url_pesquisateste = 'https://xml.movida.com.br/movida/ws3/index.php';
        $this->url_pesquisateste = 'https://ws3.movida.com.br/ws3/index.php';

        $this->xml_factory_name = 'movida';
    }


    protected function getURLResponse($request_data, $url, $url_a = null)
    {
        $url_movida = $url . "?xmlData=" . urlencode($request_data);
        $this->curl_handler = curl_init();

        $opts = array(
            CURLOPT_URL => $url_movida,
            //CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => true,
            //CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_USE_SSL => CURLUSESSL_NONE
        );
        if ($url == $this->url_pesquisar) {
            $opts[CURLOPT_CONNECTTIMEOUT] = 30;
            $opts[CURLOPT_TIMEOUT_MS] = 45000;
        }
        curl_setopt_array(

            $this->curl_handler,
            $opts
        );
        $result = curl_exec($this->curl_handler);
        curl_close($this->curl_handler);
        return $this->getURL($result);
    }


    private function getURL($result = null)
    {
        return $result;
    }
}