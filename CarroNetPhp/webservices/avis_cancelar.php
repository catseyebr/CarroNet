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
      <OTA_VehCancelRQ Target="Test" Version="1.0" SequenceNmbr="1" MaxResponses="1"
      xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehCancelRQ.xsd">
        <POS>
          <Source>
            <RequestorID Type="1" ID="LayumTravel" />
          </Source>
          <Source>
            <RequestorID Type="5" ID="57522290" />
          </Source>
        </POS>
        <VehCancelRQCore CancelType="Commit">
            <UniqueID Type="14" ID="<?php echo $this->nmbReserva; ?>" />
            <PersonName>
            	<Surname><?php echo normaliza($this->sobrenome_emissor); ?></Surname>
            </PersonName>
        </VehCancelRQCore>
      </OTA_VehCancelRQ>
    </ns:Request>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>