<?php
    function normaliza($string){
        $a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
        $b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
        $string = utf8_decode($string);
        $string = strtr($string, utf8_decode($a), $b);
        return utf8_encode($string);
    }
    
    if ($this->protecao) {
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
    $coverage = NULL;
    $opp = NULL;
    if($this->opcionais){
        $opps = explode(',',$this->opcionais);
        if(in_array('1',$opps)) {
            $opp .= '<SpecialEquipPref EquipType="13"/>';
        }
        if(in_array('2',$opps)) {
            $opp .= '<SpecialEquipPref EquipType="7"/>';
        }
        if(in_array('3',$opps)) {
            $opp .= '<SpecialEquipPref EquipType="8"/>';
        }
        if(in_array('4',$opps)) {
            $opp .= '<SpecialEquipPref EquipType="9"/>';
        }
        if(in_array('5',$opps)) {
        
        }
        if(in_array('6',$opps)) {
            $opp .= '<SpecialEquipPref EquipType="55"/>';
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
        <urn:ConfirmaReserva soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
            <AXML xsi:type="xsd:string">
                <OTA_VehResRQ Target="Production" Version="1.0" SequenceNmbr="1"
                              xmlns="http://www.opentravel.org/OTA/2003/05"
                              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                    <POS>
                        <!-- Credentials or specific parameters of partner -->
                        <Source ISOCountry="BR" ISOCurrency="BRL">
                        <RequestorID Type="1" ID=""/>
                        </Source>
                        <Source>
                        <RequestorID Type="5" ID=""/>
                        </Source>
                    </POS>
                    <!-- Availability types: All, Available, OnRequest -->
                    <VehResRQCore Status="Available">
                        <!-- Pickup and Return date and times -->
                        <VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
                            <!-- Pickup and Return locations (Stations) -->
                            <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
                            <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
                        </VehRentalCore>
                        <!-- Customer data -->
                        <Customer>
                            <Primary>
                                <PersonName>
                                    <GivenName><?php echo normaliza($this->nome_emissor); ?></GivenName>
                                    <Surname><?php echo normaliza($this->sobrenome_emissor); ?></Surname>
                                </PersonName>
                                <Telephone AreaCityCode="41" PhoneNumber="123456789"/>
                                <Email><?php echo $this->email_emissor; ?></Email>
                                <!-- Customer document, DocType="5" = CPF (Brazilian document) -->
                                <Document DocID="<?php echo $this->cpf_emissor; ?>" DocType="5"/>
                                <CitizenCountryName Code="BR"/>
                            </Primary>
                        </Customer>
                        <!-- Rental company data -->
                        <VendorPref CompanyShortName="Rental Line" Code="226"></VendorPref>
                        <!-- Group data -->
                        <VehPrefs>
                            <VehPref Code="<?php echo $this->sipp; ?>" CodeContext="SIPP"></VehPref>
                        </VehPrefs>
                        <!-- Rate code and discount coupon -->
                        <RateQualifier RateQualifier="" CorpDiscountNmbr=""/>
                    </VehResRQCore>
                    <VehResRQInfo>
                        <!-- Flight details -->
                        <ArrivalDetails TransportationCode="14" Number="<?php echo $this->nmb_voo ?>">
                            <MarketingCompany Code="<?php echo $this->cia_voo ?>"/>
                        </ArrivalDetails>
                        <!-- Coverages -->
                        <?php echo $coverage; ?>
                    </VehResRQInfo>
                </OTA_VehResRQ>
            </AXML>
        </urn:ConfirmaReserva>
    </soapenv:Body>
</soapenv:Envelope>