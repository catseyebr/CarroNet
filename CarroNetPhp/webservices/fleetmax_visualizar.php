<?php
function normaliza($string){
$a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
$b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
$string = utf8_decode($string);
$string = strtr($string, utf8_decode($a), $b);
return utf8_encode($string);
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
        <urn:ConsultaReserva soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
            <AXML xsi:type="xsd:string"><OTA_VehRetResRQ Target="Production" Version="1.0" SequenceNmbr="1"
                                                         xmlns="http://www.opentravel.org/OTA/2003/05"
                                                         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                    <POS>
                        <!-- Credentials or specific parameters of partner -->
                        <Source ISOCountry="BR" ISOCurrency="BRL">
                        <RequestorID Type="1" ID="{{$requestorName}}"/>
                        </Source>
                        <Source>
                        <RequestorID Type="5" ID="{{$requestorID}}"/>
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
                        <Vendor CompanyShortName="Rental Line" Code="226">{{$vendorName}}</Vendor>
                    </VehRetResRQInfo>
                </OTA_VehRetResRQ></AXML>
        </urn:ConsultaReserva>
    </soapenv:Body>
</soapenv:Envelope>