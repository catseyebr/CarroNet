<?php

?>
<OTA_VehAvailRateRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehAvailRateRQ.xsd" Version="1.008" MaxResponses="50">
    <POS>
        <Source ISOCountry="BR" AgentDutyCode="A3C1A13M20T">
            <RequestorID Type="4" ID="T895">
                <CompanyName Code="CP" CodeContext="E4U8" />
            </RequestorID>
        </Source>
        <Source>
            <RequestorID Type="8" ID="ZE" />
        </Source>
    </POS>
    <VehAvailRQCore Status="All">
        <VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
            <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>" CodeContext="IATA" />
            <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>" CodeContext="IATA" />
        </VehRentalCore>
        <VehPrefs>
            <VehPref Code="<?php echo $this->sipp; ?>" CodeContext="SIPP" CodePref="Only"/>
        </VehPrefs>
    </VehAvailRQCore>
</OTA_VehAvailRateRQ>
