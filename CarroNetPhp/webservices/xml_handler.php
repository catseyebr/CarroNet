<?php

require_once WS_PATH . 'xml_handler/exception.php';

Abstract Class XML_Handler {
  
  protected $_is_ok;
  protected $_type;
  protected $_haw_request_data;
  protected $_haw_received_data;
  
  protected $_xml_id;
  protected $_diaria_sem_taxa;
  protected $_taxas_diaria;
  protected $_taxas_extra;
  protected $_rate_qual;
  protected $_nmb_diarias;
  protected $_valor_total;
  protected $_protecao;
    
  protected $_devolucao;
  protected $_cadeira_bebe;
  protected $_gps;
  
  protected $_vis_name;
  protected $_vis_surname; 
  protected $_vis_dtareti;
  protected $_vis_dtadevo; 
  protected $_vis_errorTrack; 
  protected $_vis_ljreti;
  protected $_vis_ljdevo; 
  protected $_vis_ciaaerea;
  protected $_vis_nmbvoo;
  protected $_sipp_grp;
  protected $_status_reserva;

  protected $_reti_id;
  protected $_devo_id;

  protected $_arr_dados;
  protected $_opcionais;

  protected $_parser_data;
  
  public function __get ($name) {
    $name = '_' . $name;
    if (property_exists('XML_Handler', $name)) {
      return $this->{$name};
    } else {
      throw new XML_Handler_Exception(XML_Handler_Exception::NO_PROPERTY);
    }
  }
  
  public function __set ($name, $value) {
    throw new XML_Handler_Exception(XML_Handler_Exception::NO_PROPERTY);
  }
  
  public function __construct ($xml_data) {} 
  
  public function strict () {
  	if ($this->_is_ok != 1) {
  		$this->_is_ok = 0;
		}
	}
}