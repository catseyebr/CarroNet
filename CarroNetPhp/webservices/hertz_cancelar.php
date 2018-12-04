<?php
    function normaliza($string){
        $a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
        $b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
        $string = utf8_decode($string);
        $string = strtr($string, utf8_decode($a), $b);
        return utf8_encode($string);
    }
?>
<OTA_VehCancelRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehCancelRQ.xsd" Version="1.008">
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
    <VehCancelRQCore CancelType="Book">
        <UniqueID Type="14" ID="<?php echo $this->nmbReserva; ?>"/>
        <PersonName>
            <Surname><?php echo normaliza($this->sobrenome_emissor); ?></Surname>
        </PersonName>
    </VehCancelRQCore>
</OTA_VehCancelRQ>
