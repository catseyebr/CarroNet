<?php
function normaliza($string){
    $a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
    $b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
    $string = utf8_decode($string);
    $string = strtr($string, utf8_decode($a), $b);
    return utf8_encode($string);
}
?>
<!-- Environment flag: Helps us identify in which environment this request is being processed -->
<OTA_VehRetResRQ Target="Production" Version="1.0" SequenceNmbr="1"
                 xmlns="http://www.opentravel.org/OTA/2003/05"
                 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <POS>
        <!-- Credentials or specific parameters of partner -->
        <Source ISOCountry="BR" ISOCurrency="BRL">
        <RequestorID Type="1" ID="Car Rental" MessagePassword="!nf0$!$t3m@$#C@rR3nt@l#2ol7"/>
        </Source>
        <Source>
        <RequestorID Type="5" ID="1"/>
        </Source>
    </POS>
    <VehRetResRQCore>
        <!-- Confirmation reservation code  -->
        <UniqueID Type="14" ID="<?php echo $this->nmbReserva; ?>"/>
        <PersonName>
            <GivenName><?php echo normaliza($this->nome_emissor); ?></GivenName>
            <Surname><?php echo normaliza($this->sobrenome_emissor); ?></Surname>
        </PersonName>
    </VehRetResRQCore>
    <VehRetResRQInfo>
        <!-- Rental company data -->
        <Vendor CompanyShortName="{{$vendorName}}" Code="{{$vendorCode}}">{{$vendorName}}</Vendor>
    </VehRetResRQInfo>
</OTA_VehRetResRQ>