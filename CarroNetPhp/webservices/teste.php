<?php
$qStart = microtime(true);
include '../common.php';
require_once ("../Connections/carroaluguel.php");
define("WS_PATH", '../novo_xml/');
require_once WS_PATH . 'webservice_handler/factory.php';

$a = WebService_Handler_Factory::create('avis');
$b = array(
"sobrenome_emissor"=>  "ASSIS",
"nmbReserva"=> "00587890BR2"
);
//$a->setData($b)->visualizar();
var_dump($a->setData($b)->visualizar());
?>