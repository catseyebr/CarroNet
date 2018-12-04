<?php
Class Parser {
  private $file;
  public function __construct ($file) {
    if (file_exists($file)) {
      $this->file = $file;
    } else {
      throw new Exception("\r\n ----- Erro na leitura do arquivo! ----- \r\n"); 
    }
  }
  
  public function setData ($data) {
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }
    }
    
    return $this;
  }
  
  public function getContent () {
    ob_start();
    $fgc = file_get_contents($this->file);
    eval("?>" . $fgc . "<?php echo '';"); //"
    $buffer = ob_get_contents();
    @ob_end_clean();
    return $buffer;
  }
}
?>
