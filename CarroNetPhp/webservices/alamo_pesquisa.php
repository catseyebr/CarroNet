<?php
$coverage = '<CoveragePrefs>';
$coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="ALL" />';
$coverage .= '</CoveragePrefs>';
if($this->ratequal != ''){
  $ratequalifier = $this->ratequal;
}else{
  //$ratequalifier = 127370;
  $ratequalifier = 136435;
}
if($this->promocod == 'BB058875'){
    $ratequalifier = 136902;
    //$ratequalifier = 131819;
    $this->promocod = NULL;
}
?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <soap:Header>
    <Usuario xmlns="http://www.unidas.com.br/">
      <Acordo>1250</Acordo>
      <Senha>0128000105</Senha>
    </Usuario>
  </soap:Header>
  <soap:Body>
    <OtaVehAvailRate xmlns="http://www.unidas.com.br/">
      <OTA_VehAvailRateRQ Version="0" xmlns="http://www.opentravel.org/OTA/2003/05">
        <VehAvailRQCore Status="Available">
          <VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
            <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
            <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
          </VehRentalCore>
          <VehPrefs>
            <VehPref Code="<?php echo $this->sipp; ?>"/>
          </VehPrefs>
			<RateQualifier RateQualifier="<?php echo $ratequalifier; ?>"/>
        </VehAvailRQCore>
        <VehAvailRQInfo>
          <?php echo $coverage; ?>
        </VehAvailRQInfo>
      </OTA_VehAvailRateRQ>
    </OtaVehAvailRate>
  </soap:Body>
</soap:Envelope>