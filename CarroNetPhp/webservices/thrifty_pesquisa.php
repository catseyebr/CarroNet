<?php 
$ar_cod = ($this->arCondicionado)?"S":"N";
$transm = ($this->transmissao = "Automatic")?"A":"M";
?>
<OTA_VehAvailRateRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehAvailRateRQ.xsd" Version="1.0" MaxResponses="5">
  <POS>
    <Source ISOCountry="BR" AgentDutyCode="513896">
      <RequestorID Type="4" ID="40358">
        <CompanyName Code="CP" CodeContext="57512770"/>
      </RequestorID>
    </Source>
  </POS>
  <VehAvailRQCore Status="Available">
    <VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
      <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
      <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
    </VehRentalCore>
    <VehPrefs>
      <VehPref AirConditionInd="<?php echo $ar_cod; ?>" TransmissionType="<?php echo $transm; ?>">
        <VehType  VehicleCategory="<?php echo $this->tipo; ?>"  DoorCount="<?php echo $this->door; ?>" />
        <VehClass Size="<?php echo $this->categoria; ?>"/>
      </VehPref>
    </VehPrefs>
  </VehAvailRQCore>
</OTA_VehAvailRateRQ>