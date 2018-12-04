<?php
  $prefs = 'Only';
  if(isset($this->preferences) && $this->preferences == 'preferred' && !$this->sipp){
    $prefs = 'Preferred';
  }
  if($this->sipp){
    $sipp = $this->sipp;
    $tipo = $this->tipo;
    $door = $this->door;
    $categoria = $this->categoria;
    $arCondicionado = $this->arCondicionado;
    $transmissao = $this->transmissao;
  }else{
    $sipp = "EDMR";
    $tipo = "1";
    $door = "4";
    $categoria = "4";
    $arCondicionado = "True";
    $transmissao = "Manual";
  }
  /*if(isset($this->ratequalifier) && $this->ratequalifier == 'ALL'){
    $ratequalifier = '';
  }else if(is_array($ratequalifier) && isset($ratequalifier['RateQualifier'])){
    if(isset($ratequalifier['CorpDiscountNmbr'])){
      $ratequalifier = '<RateQualifier CorpDiscountNmbr="'.$ratequalifier['CorpDiscountNmbr'].'" RateQualifier="'.$ratequalifier['RateQualifier'].'"/>';
    }else{
      $ratequalifier = '<RateQualifier CorpDiscountNmbr="H570800" RateQualifier="'.$ratequalifier['RateQualifier'].'"/>';
    }
  }
  else{
    $ratequalifier = '<RateQualifier RateCategory="2" CorpDiscountNmbr="H570800" RateQualifier="EVI"/>';
  }*/
  $ratequalifier = '<RateQualifier RateCategory="6" RateQualifier=" "/>';
  if(isset($this->citizencountryname)){
    $citizencountryname = $this->citizencountryname;
  }else{
    $citizencountryname = 'BR';
  }

?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:xsi="http://www.w3.org/1999/XMLSchema-instance" xmlns:xsd="http://www.w3.org/1999/XMLSchema">
  <SOAP-ENV:Header>
    <ns:credentials xmlns:ns="http://wsg.avis.com/wsbang/authInAny">
      <ns:userID ns:encodingType="xsd:string">LayumTravel</ns:userID>
      <ns:password ns:encodingType="xsd:string">CWDmNqsIrG=p</ns:password>
    </ns:credentials>
    <ns:WSBang-Roadmap xmlns:ns="http://wsg.avis.com/wsbang"/>
  </SOAP-ENV:Header>
  <SOAP-ENV:Body>
    <ns:Request xmlns:ns="http://wsg.avis.com/wsbang">
      <OTA_VehAvailRateRQ Target="Test" Version="1.0" SequenceNmbr="1" MaxResponses="10"
      xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehAvailRateRQ.xsd">
        <POS>
          <Source>
            <RequestorID Type="1" ID="LayumTravel" />
          </Source>
          <Source>
            <RequestorID Type="5" ID="57522290" />
          </Source>
        </POS>
        <VehAvailRQCore Status="Available">
          <VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
            <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
            <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
          </VehRentalCore>
          <VendorPrefs>
            <VendorPref CompanyShortName="Avis">Avis Rent-A-Car</VendorPref>
          </VendorPrefs>
          <VehPrefs>
          <VehPref AirConditionInd="<?php echo $arCondicionado ?>" TransmissionType="<?php echo $transmissao; ?>" TypePref="<?php echo $prefs;?>" ClassPref="<?php echo $prefs;?>"
            AirConditionPref="<?php echo $prefs;?>" TransmissionPref="<?php echo $prefs;?>">
            <VehType VehicleCategory="<?php echo $tipo; ?>" DoorCount="<?php echo $door; ?>" />
              <VehClass Size="<?php echo $categoria; ?>" />
          	<VehGroup GroupType="SIPP" GroupValue="<?php echo $sipp; ?>" />

            </VehPref>

          </VehPrefs>
          <?php echo $ratequalifier; ?>
        </VehAvailRQCore>
        <VehAvailRQInfo>
          
          <Customer>
            <Primary>
              <CitizenCountryName Code="<?php echo $citizencountryname; ?>" />
            </Primary>
          </Customer>
        </VehAvailRQInfo>
      </OTA_VehAvailRateRQ>
    </ns:Request>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>