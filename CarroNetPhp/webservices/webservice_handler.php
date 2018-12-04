<?php
require_once WS_PATH . 'parser.php';
require_once WS_PATH . 'webservice_handler/exception.php';
require_once WS_PATH . 'xml_handler/factory.php';
ini_set('default_socket_timeout', 20);
Abstract Class WebService_Handler {
  public $result;

  protected $xml_pesquisar;
  protected $url_pesquisar;
  protected $url_pesquisar_action;
  
  protected $xml_reservar; 
  protected $url_reservar;
  
  protected $xml_alterar;
  protected $url_alterar;
  
  protected $xml_cancelar;
  protected $url_cancelar;
    
  protected $xml_vasualizar;
  protected $url_vasualizar;
  
  protected $xml_factory_name;
  
  private $parser_data = array();
  private $last_request_data;
  private $last_received_data;
  
  protected function getData () {
    return $this->parser_data;
  }
  
  public function getLastRequest () {
    return $this->last_request_data;
  }
  
  public function getLastReceived () {
    return $this->last_received_data;
  }
  
  public function setData ($data, $value = NULL) {
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        if (is_string($key)) {
          $this->parser_data[$key] = $value;
        }
      }
    } else if (is_string($data) && !empty($value)) {
      $this->parser_data[$data] = $value;
    }
    return $this;
  }
  
  public function pesquisar () {
    return $this->returnResponse($this->xml_pesquisar, $this->url_pesquisar, $this->url_pesquisar_action);
  }

    public function pesquisateste () {
        return $this->returnResponse($this->xml_pesquisateste, $this->url_pesquisateste, $this->url_pesquisar_action);
    }
  
  public function reservar () {
    return $this->returnResponse($this->xml_reservar, $this->url_reservar, 1);
  }
  
  public function alterar () {
    return $this->returnResponse($this->xml_alterar, $this->url_alterar);
  }
  
  public function cancelar () {
    return $this->returnResponse($this->xml_cancelar, $this->url_cancelar);
  }
  
  public function visualizar () {                                                                                                             
    return $this->returnResponse($this->xml_visualizar, $this->url_visualizar);
  }

    public function lojas () {
        return $this->returnResponse($this->xml_lojas, $this->url_lojas);
    }

	public function loja_pesquisa () {
		return $this->returnResponse($this->xml_lojas_pesquisa, $this->url_lojas_pesquisa);
	}

    public function grupos_pesquisa () {
        return $this->returnResponse($this->xml_grupos_pesquisa, $this->url_grupos_pesquisa);
    }

    public function stations () {
        return $this->returnResponse($this->xml_stations, $this->url_stations, $this->url_stations_action);
    }

    public function station_details () {
        return $this->returnResponse($this->xml_station_details, $this->url_station_details, $this->url_station_details_action);
    }

    public function countries () {
        return $this->returnResponse($this->xml_countries, $this->url_countries, $this->url_countries_action);
    }

    public function cities () {
        return $this->returnResponse($this->xml_cities, $this->url_cities, $this->url_cities_action);
    }

    public function groups () {
        return $this->returnResponse($this->xml_groups, $this->url_groups, $this->url_groups_action);
    }

    public function groups_details () {
        return $this->returnResponse($this->xml_groups_details, $this->url_groups_details, $this->url_groups_details_action);
    }
  
  protected function returnResponse ($base_xml, $url, $url_a = NULL) {
      $qStart = microtime(TRUE);
    if (empty ($this->xml_factory_name)) {
      throw new WebService_Handler_Exception(WebService_Handler_Exception::NO_XML_FACTORY);
    } else if (empty ($base_xml)) {
      throw new WebService_Handler_Exception(WebService_Handler_Exception::NO_REQUEST_XML);
    } else if (empty ($url)) {
      throw new WebService_Handler_Exception(WebService_Handler_Exception::NO_REQUEST_URL);
    }
    
    $parser = new Parser($base_xml);
    $this->last_request_data  = $parser->setData($this->parser_data)->getContent();
    $this->last_received_data = $this->getURLResponse($this->last_request_data, $url,$url_a);
    $this->result = XML_Handler_Factory::create($this->xml_factory_name, $this->last_received_data, $this->last_request_data, $this->parser_data);
    return $this->result;
  }
  
  protected function getURLResponse ($request_data, $url,$url_a) {
    throw new WebService_Handler_Exception(WebService_Handler_Exception::NOT_OVERRIDED);
  }
}