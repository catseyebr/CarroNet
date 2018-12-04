<?php 
if (!function_exists("t2d")) {
function t2d($hora){ 
    $hora_aux = explode(':', $hora);
	$h = ($hora_aux[0] . '.' . ($hora_aux[1] * 100 / 60));	
    return $hora_aux[0];
}}

$dtareti = split("T",$this->dataRetirada); 
$dta1 = split("-",$dtareti[0]);
$dtaRetirada = $dta1[1]."-".$dta1[2]."-".$dta1[0];
$horaRetirada = t2d($dtareti[1]);

$dtadevo = split("T",$this->dataDevolucao); 
$dta2 = split("-",$dtadevo[0]);
$dtaDevolucao = $dta2[1]."-".$dta2[2]."-".$dta2[0];
$horaDevolucao = t2d($dtadevo[1]);
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SimpleSearchGroupRental xmlns="http://tempuri.org/">
      <withdrawalCity><?php echo $this->cidadeRetirada; ?></withdrawalCity>
      <deliveryCity><?php echo $this->cidadeDevolucao; ?></deliveryCity>
      <startDate><?php echo $dtareti[0]; ?></startDate>
      <endDate><?php echo $dtadevo[0]; ?></endDate>
      <startTime><?php echo $horaRetirada; ?></startTime>
      <endTime><?php echo $horaDevolucao; ?></endTime>
      <code>97</code>
      <legalcode>06110474000172</legalcode>
      <groupsCodes><?php echo $this->tipordcar;?></groupsCodes>
      <rentalsCodes><?php echo $this->loc_rdcars;?></rentalsCodes>
    </SimpleSearchGroupRental>
  </soap:Body>
</soap:Envelope>

