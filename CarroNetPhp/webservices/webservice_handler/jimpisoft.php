<?php
require_once WS_PATH . 'webservice_handler.php';
require_once WS_PATH . 'parser.php';

Final Class WebService_Handler_Jimpisoft extends WebService_Handler
{

    public function __construct()
    {
        $this->xml_pesquisar = WS_PATH . 'jimpisoft_pesquisa.php';
        $this->url_pesquisar_action = 'http://195.23.2.243/RentwayWS/getMultiplePrices.asmx';
        $this->url_pesquisar = 'http://www.jimpisoft.pt/Rentway_Reservations_WS/getMultiplePrices/MultiplePrices';

        $this->xml_reservar = WS_PATH . 'jimpisoft_reserva.php';
        $this->url_reservar = 'http://www.jimpisoft.pt/Rentway_Reservations_WS/getPriceAndAvailability/PriceAndAvailability';

        $this->xml_alterar = WS_PATH . '';
        $this->url_alterar = '';

        $this->xml_cancelar = WS_PATH . 'jimpisoft_cancelar.php';
        $this->url_cancelar = 'http://www.jimpisoft.pt/Rentway_Reservations_WS/getPriceAndAvailability/PriceAndAvailability';

        $this->xml_visualizar = WS_PATH . 'jimpisoft_visualizar.php';
        $this->url_visualizar = 'http://www.jimpisoft.pt/Rentway_Reservations_WS/getPriceAndAvailability/PriceAndAvailability';

        $this->xml_groups = WS_PATH . 'jimpisoft_groups.php';
        $this->url_groups_action = 'http://195.23.2.243/RentwayWS/getGroups.asmx';
        $this->url_groups = 'http://www.jimpisoft.pt/Rentway_Reservations_WS/getGroups/getGroups';

        $this->xml_groups_details = WS_PATH . 'jimpisoft_groups_details.php';
        $this->url_groups_details_action = 'http://195.23.2.243/RentwayWS/getGroupDetails.asmx';
        $this->url_groups_details = 'http://www.jimpisoft.pt/Rentway_Reservations_WS/getGroupDetails/GroupDetails';

        $this->xml_stations = WS_PATH . 'jimpisoft_stations.php';
        $this->url_stations_action = 'http://195.23.2.243/RentwayWS/getStations.asmx';
        $this->url_stations = 'http://www.jimpisoft.pt/Rentway_Reservations_WS/getStations/getStations';

        $this->xml_station_details = WS_PATH . 'jimpisoft_station_details.php';
        $this->url_stations_details_action = 'http://195.23.2.243/RentwayWS/getStationDetails.asmx';
        $this->url_station_details = 'http://www.jimpisoft.pt/Rentway_Reservations_WS/getStationDetails/StationDetails';

        $this->xml_countries = WS_PATH . 'jimpisoft_countries.php';
        $this->url_countries_action = 'http://jsserver20.jimpisoft.pt/Rentway_WS_Demo/getCountries.asmx';
        //$this->url_countries_action = 'http://195.23.2.243/RentwayWS/getCountries.asmx';
        $this->url_countries = 'http://www.jimpisoft.pt/Rentway_Reservations_WS/getCountries/getCountries';

        $this->xml_cities = WS_PATH . 'jimpisoft_cities.php';
        $this->url_cities_action = 'http://jsserver20.jimpisoft.pt/Rentway_WS_Demo/getCities.asmx';
        //$this->url_cities_action = 'http://195.23.2.243/RentwayWS/getCities.asmx';
        $this->url_cities = 'http://www.jimpisoft.pt/Rentway_Reservations_WS/getCities/getCities';

        $this->xml_factory_name = 'jimpisoft';
    }


    protected function getURLResponse($request_data, $url, $url_a = NULL)
    {
        $this->curl_handler = curl_init($url_a);
        $opts = array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPPROXYTUNNEL => true,
            CURLOPT_POSTFIELDS => $request_data,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_USERPWD => 'carroaluguel:carroaluguel',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/xml; charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                'SOAPAction: "' . $url . '"',
                "Content-length: " . strlen($request_data),
            ),
            CURLOPT_SSL_VERIFYPEER => true
        );
        if ($url == $this->url_pesquisar) {
            $opts[CURLOPT_CONNECTTIMEOUT] = 1;
            $opts[CURLOPT_TIMEOUT_MS] = 15000;
        }
        curl_setopt_array(

            $this->curl_handler,
            $opts
        );
        $result = curl_exec($this->curl_handler);
        return $this->getURL($result);
    }


    private function getURL($result = null)
    {
        return $result;
    }
}