<?php
function normaliza($string){
$a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
$b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
$string = utf8_decode($string);
$string = strtr($string, utf8_decode($a), $b);
return utf8_encode($string);
}
	    $end_emissor = 'Vinte Quatro de Maio 1285';
		$cidade_emissor = 'Curitiba';
		$cep_emissor = '80230080';
		$estado_emissor = 'PR';
$coverage = '<CoveragePrefs>';
$coverage .= '<CoveragePref CoverageType="7"/>';
$coverage .= '</CoveragePrefs>';

if($this->protecao == 1){
    $coverage = '<CoveragePrefs>';
    $coverage .= '<CoveragePref CoverageType="7"/>';
    $coverage .= '<CoveragePref CoverageType="1"/>';
    $coverage .= '</CoveragePrefs>';
}
if($this->promocod) {
    $ratequalifier = '<RateQualifier RateCategory="6" CorpDiscountNmbr="H570800" RateQualifier="'.$this->rate_qual.'" PromotionCode="'.$this->promocod.'"/>';
}else{
    $ratequalifier = '<RateQualifier RateCategory="6" CorpDiscountNmbr="H570800" RateQualifier="'.$this->rate_qual.'"/>';
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
      <OTA_VehResRQ Target="Test" Version="1.0" SequenceNmbr="1" MaxResponses="1"
      xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehResRQ.xsd">
        <POS>
          <Source>
            <RequestorID Type="1" ID="LayumTravel" />
          </Source>
          <Source>
            <RequestorID Type="5" ID="57522290" />
          </Source>
			<Source>
				<RequestorID Type="11" ID="60807334987"/>
			</Source>
		</POS>
        <VehResRQCore Status="All">
          <VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
            <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
            <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
          </VehRentalCore>
          <Customer>
            <Primary>
				<PersonName>
                  <GivenName><?php echo normaliza(substr($this->nome_emissor,0,27)); ?></GivenName>
                  <Surname><?php echo normaliza(substr($this->sobrenome_emissor,0,27)); ?></Surname>
				      </PersonName>
				      <Telephone PhoneTechType="1" AreaCityCode="405" PhoneNumber="5555828"/>
				      <Email EmailType="2"><?php echo $this->email_emissor; ?></Email>
				      <Address Type="2">
                <StreetNmbr><?php echo normaliza($end_emissor); ?></StreetNmbr>
                <CityName><?php echo normaliza($cidade_emissor); ?></CityName>
                <PostalCode><?php echo normaliza($cep_emissor); ?></PostalCode>
                <StateProv><?php echo normaliza($estado_emissor); ?></StateProv>
				      </Address>
              <CitizenCountryName Code="BR"/>
            </Primary>
          </Customer>
          <VendorPref CompanyShortName="Avis">Avis Rent-A-Car</VendorPref>
          <VehPref AirConditionInd="<?php echo $this->arCondicionado ?>" TransmissionType="<?php echo $this->transmissao; ?>" TypePref="Only" ClassPref="Only"
            AirConditionPref="Only" TransmissionPref="Only">
            <VehType VehicleCategory="<?php echo $this->tipo; ?>" DoorCount="<?php echo $this->door; ?>" />
              <VehClass Size="<?php echo $this->categoria; ?>" />
          	<VehGroup GroupType="SIPP" GroupValue="<?php echo $this->sipp; ?>" />
            </VehPref>
            <?php echo $ratequalifier; ?>
        </VehResRQCore>

        <VehResRQInfo>
            <?php echo $coverage; ?>
			<?php if($this->cia_voo != "" && $this->nmb_voo != ""){?>
			<ArrivalDetails TransportationCode="14" Number="<?php echo $this->nmb_voo ?>">
				<MarketingCompany Code="<?php echo $this->cia_voo ?>"/>
            </ArrivalDetails>
			<?php } ?>
        </VehResRQInfo>

      </OTA_VehResRQ>
    </ns:Request>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>