<?php
header('Content-Type: aplication/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set("allow_url_fopen", 1);
ini_set("allow_url_include", 1);
define("WS_PATH", '../webservices/');

require_once WS_PATH . 'webservice_handler/factory.php';

$a = WebService_Handler_Factory::create('localiza');
$b = array(
"dataRetirada" 			=>  "2018-07-09T12:00:00",
"dataDevolucao" 		=>  "2018-07-11T12:00:00",
"locadora_short" 		=>  "localiza",
"cidadeRetirada"		=>  "AACWB",
"cidadeDevolucao"		=>  "AACWB",
"arCondicionado" 		=>  TRUE,
"locadora_short" 		=>  "localiza",
"loc" 					=> "109",
"tipordcar"				=> "1",
"tipo"			    	=> "19",
"categoria"				=>  11,
"transmissao"			=> "Manual",
"door"					=>  "4",
"nome_emissor"			=>  "EDUARDO",
"sobrenome_emissor"		=>  "Assis",
"nomecompleto_emissor"	=> "EDUARDO ASSIS",
"email_emissor"			=>  "catseyebr@gmail.com",
"end_emissor"			=> "Nunes machado, 645 - apto 206",
"cidade_emissor"		=>  "Curitiba",
"estado_emissor"		=> "PR",
"cep_emissor"			=> "80250000",
"extra_child"			=>  1,
"extra_gps"				=>  1,
"cia_voo"				=>  "G3",
"nmb_voo"				=>  "1097",
"sipp"					=> "EDMR",
"nmbReserva"			=> "AVO15HIB4A",
"usuario"				=> "svc-rsvcalhom",
"senha"					=> "DRS1TDVv5NU",
"nomeCidade"			=> "Nova",

);
//$a->setData($b)->pesquisar();
//$pesquisa = $a->setData($b)->pesquisar();
//$b['arr_dados'] = $pesquisa->arr_dados;
//echo '<pre>';
//var_dump($a->setData($b)->cancelar());
//echo '</pre>';
$pesquisa = $a->setData($b)->pesquisar();
$retorno = 'echo';
echo json_encode($retorno);
?>