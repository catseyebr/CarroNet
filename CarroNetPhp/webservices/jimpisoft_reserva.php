<?php
function normaliza($string){
  $a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
  $b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
  $string = utf8_decode($string);
  $string = strtr($string, utf8_decode($a), $b);
  return utf8_encode($string);
}
$unique_dado = $this->unique_id. rand(4, 5);
?>
<Request xmlns="http://www.thermeon.com/webXG/xml/webxml/" referenceNumber="<?php echo mktime((int)date('h'), (int)date('i'), (int)date('s'), (int)date('m'), (int)date('d'), (int)date('y'));?>" version="2.2700">
  <NewReservationRequest confirmAvailability="true">
    <Pickup locationCode="<?php echo $this->cidadeRetirada; ?>" dateTime="<?php echo $this->dataRetirada; ?>"/>
    <Return locationCode="<?php echo $this->cidadeDevolucao; ?>" dateTime="<?php echo $this->dataDevolucao; ?>"/>
    <Source confirmationNumber="<?php echo $unique_dado; ?>" countryCode="BR"/>
    <Vehicle classCode="<?php echo $this->sipp; ?>"/>
    <Renter>
      <RenterName firstName="<?php echo normaliza(substr($this->nome_emissor,0,12)); ?>" lastName="<?php echo normaliza(substr($this->sobrenome_emissor,0,18)); ?>"/>
      <Address>
        <Email><?php echo $this->email_emissor; ?></Email>
        <HomeTelephoneNumber><?php echo $this->fone_emissor; ?></HomeTelephoneNumber>
        <CellTelephoneNumber><?php echo $this->cel_emissor; ?></CellTelephoneNumber>
      </Address>
      <?php if($this->cpf_emissor != ''){?>
      <AdditionalIdentification>
        <Number><?php echo $this->cpf_emissor; ?></Number>
        <Description>CPF</Description>
        <Country>BR</Country>
      </AdditionalIdentification>
      <?php } ?>
    </Renter>
    
    <QuotedRate rateID="<?php echo $this->rate_qual; ?>" classCode="<?php echo $this->sipp; ?>" corporateRateID="LAYUM"/>
    <DesiredOptions></DesiredOptions>
    <?php if($this->cia_voo != "" && $this->nmb_voo != ""){?>
      <Flight airlineCode="<?php echo $this->cia_voo; ?>" flightNumber="<?php echo $this->nmb_voo; ?>"/>
    <?php } ?>
  </NewReservationRequest>
</Request>