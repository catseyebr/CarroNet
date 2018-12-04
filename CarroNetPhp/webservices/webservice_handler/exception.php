<?php
Final Class WebService_Handler_Exception extends Exception {
  const DEFALUT_ERROR  = 0;
  const NOT_OVERRIDED  = 1;
  const NO_REQUEST_XML = 2;
  const NO_REQUEST_URL = 3;
  const NO_XML_FACTORY = 4;
  const NO_WS_FACTORY  = 5;
  const NO_METHOD      = 6;
  
  private $errors = array(
      0 => 'Erro ao processar dados!',
      1 => 'Chamada de método não sobrescrito pela classe filha!',
      2 => 'XML de requisição não informado!',
      3 => 'URL de requisição não informada!',
      4 => 'Termo da Classe de tratamento de resultado não encontrado!',
      5 => 'Termo da Classe de tratamento não encontrado!',
      6 => 'Método não existe!',
  );
  
  public function __construct ($errno = 0) {
    if (isset($this->errors[$errno])) {
      parent::__construct(' ----- ' . $this->errors[$errno] . ' ----- ');
    } else {
      parent::__construct(' ----- ' . $this->errors[0] . ' N° de erro informado: ' . $errno . ' ----- ');
    }
  }
}