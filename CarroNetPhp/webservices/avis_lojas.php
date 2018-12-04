<?php
function normaliza($string){
$a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
$b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
$string = utf8_decode($string);
$string = strtr($string, utf8_decode($a), $b);
return utf8_encode($string);
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
      <OTA_VehLocSearchRQ Target="Test" Version="1.0" SequenceNmbr="1" MaxResponses="1"
      xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehLocSearchRQ.xsd">
        <POS>
          <Source/>
        </POS>
            <VehLocSearchCriterion>
                <Address>
                    <CityName>Ilheus</CityName>
                    <StateProv StateCode="BA"/>
                    <CountryName Code="BR" />
                </Address>
                <Radius DistanceMax="400" DistanceMeasure="Miles"/>
            </VehLocSearchCriterion>
            <Vendor Code="Avis"/>
          <TPA_Extensions>
              <SortOrderType>DESCENDING</SortOrderType>
              <TestLocationType>NO</TestLocationType>
              <LocationStatusType>OPEN</LocationStatusType>
              <LocationType>RENTAL</LocationType>
          </TPA_Extensions>
      </OTA_VehLocSearchRQ>
    </ns:Request>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>