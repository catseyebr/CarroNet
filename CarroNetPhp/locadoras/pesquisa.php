<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'ConsultaWs.php';
$consulta = new ConsultaWs($_GET);
$consulta->index();
?>