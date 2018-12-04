<?php
    function normaliza($string){
        $a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
        $b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
        $string = utf8_decode($string);
        $string = strtr($string, utf8_decode($a), $b);
        return utf8_encode($string);
    }
    $coverage = '<CoveragePrefs>';
    $coverage .= '<CoveragePref CoverageType="24"/>';
    $coverage .= '</CoveragePrefs>';
    
    if($this->protecao == 1){
        $coverage = '<CoveragePrefs>';
        $coverage .= '<CoveragePref CoverageType="24"/>';
        $coverage .= '<CoveragePref CoverageType="1"/>';
        $coverage .= '</CoveragePrefs>';
    }
?>
<OTA_VehModifyRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehModifyRQ.xsd" Version="1.008">
    <POS>
        <Source PseudoCityCode="T895" ISOCountry="BR" AgentDutyCode="A3C1A13M20T">
            <RequestorID Type="4" ID="T895">
                <CompanyName Code="CP" CodeContext="E4U8" />
            </RequestorID>
        </Source>
        <Source>
            <RequestorID Type="8" ID="ZE" />
        </Source>
    </POS>
    <VehModifyRQCore Status="Confirmed" ModifyType="Book">
        <UniqueID Type="14" ID="<?php echo $this->nmbReserva; ?>"/>
        <VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
            <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>" CodeContext="IATA" />
            <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>" CodeContext="IATA" />
        </VehRentalCore>
        <Customer>
            <Primary>
                <PersonName>
                    <GivenName><?php echo normaliza($this->nome_emissor); ?></GivenName>
                    <Surname><?php echo normaliza($this->sobrenome_emissor); ?></Surname>
                </PersonName>
                <Telephone PhoneTechType="1" AreaCityCode="41" PhoneNumber="<?php echo $this->fone_emissor; ?>" />
                <Email><?php echo $this->email_emissor; ?></Email>
                <Document DocType="5" DocID="<?php echo $this->cpf_emissor; ?>"/>
            </Primary>
        </Customer>
        <VehPref Code="<?php echo $this->sipp; ?>" CodeContext="SIPP"></VehPref>
        <RateQualifier TravelPurpose="" CorpDiscountNmbr="" RateQualifier="<?php echo $this->rate_qual; ?>" PromotionCode=""/>
    </VehModifyRQCore>
</OTA_VehModifyRQ>
