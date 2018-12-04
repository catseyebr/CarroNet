<?php

Final Class XML_Handler_Exception extends Exception {
  const DEFALUT_ERROR  = 0;
  const NOT_OVERRIDED  = 1;
  const NO_XML_FACTORY = 2;
  const NO_METHOD      = 3;
  const NO_PROPERTY    = 4;
  
  private $errors = array(
      0 => 'Erro ao processar dados!',
      1 => 'Chamada de método não sobrescrito pela classe filha!',
      2 => 'Termo da Classe de tratamento não encontrado!',
      3 => 'Método não existe!',
      4 => 'Propriedade não existe!',
  );
  
  public function __construct ($errno = 0) {
    if (isset($this->errors[$errno])) {
      parent::__construct(' ----- ' . $this->errors[$errno] . ' ----- ');
    } else {
      parent::__construct(' ----- ' . $this->errors[0] . ' N° de erro informado: ' . $errno . ' ----- ');
    }
  }
}
?>