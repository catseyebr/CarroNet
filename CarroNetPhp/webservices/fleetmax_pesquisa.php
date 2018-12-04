<?php
    if($this->protecao) {
        if ($this->protecao == '1') {
            $coverage = '<CoveragePrefs>';
            $coverage .= '<CoveragePref CoverageType="1"/>';
            $coverage .= '<CoveragePref CoverageType="' . $this->protecao . '"/>';
            $coverage .= '</CoveragePrefs>';
        } else {
            $coverage = '<CoveragePrefs>';
            $coverage .= '<CoveragePref CoverageType="' . $this->protecao . '"/>';
            $coverage .= '</CoveragePrefs>';
        }
    }
?>
<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:WbsTransfXMLIntf-IWbsTransfXML">
    <soapenv:Header>
        <credentials>
            <userID>aluguelcar</userID>
            <password>car2017</password>
        </credentials>
    </soapenv:Header>
    <soapenv:Body>
        <urn:ConsultaDisponibilidade soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
            <AXML xsi:type="xsd:string">
                <OTA_VehAvailRateRQ Target="Production" Version="1.0" SequenceNmbr="1" MaxResponses="100" xmlns="http://www.opentravel.org/OTA/2003/05">
                    <!-- Availability types: All, Available, OnRequest -->
                    <VehAvailRQCore Status="Available">
                        <!-- Pickup and Return date and times -->
                        <VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
                            <!-- Pickup and Return locations (Stations) -->
                            <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
                            <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
                        </VehRentalCore>
                        <VehPrefs>
                            <!-- Group code -->
                            <VehPref Code="<?php echo $this->sipp; ?>" CodeContext="SIPP"/>
                        </VehPrefs>
                        <?php echo $coverage; ?>
                    </VehAvailRQCore>
                </OTA_VehAvailRateRQ>
            </AXML>
        </urn:ConsultaDisponibilidade>
    </soapenv:Body>
</soapenv:Envelope>