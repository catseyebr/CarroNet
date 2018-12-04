<?php
if($this->protecao == '1') {
    $coverage = '<PricedCoverages>';
    $coverage .= '<PricedCoverage>';
    $coverage .= '<Coverage CoverageType="1" />';
    $coverage .= '</PricedCoverage>';
    $coverage .= '<PricedCoverage>';
    $coverage .= '<Coverage CoverageType="24" />';
    $coverage .= '</PricedCoverage>';
    $coverage .= '</PricedCoverages>';
}else{
  $coverage = '<PricedCoverages>';
  $coverage .= '<PricedCoverage>';
  $coverage .= '<Coverage CoverageType="24" />';
  $coverage .= '</PricedCoverage>';
  $coverage .= '</PricedCoverages>';
}
$lojas_aeroporto = array(951,1032,1033,1039,1076,1087,2492,2493,2494,3182,30575,30627,30628,30639,30642,31288);
if(in_array($this->lojaRetirada, $lojas_aeroporto)){
    $obs = 'Solicitação para o aeroporto';
}else{
    $obs = '';
}

$nome_completo = $this->nome_emissor.' '.$this->sobrenome_emissor;

if($this->rateDistance > 1){
    $rateDistance = " Quantity='".$this->rateDistance."'";
    $rateDistanceTag = 'false';
}else{
    $rateDistance = "";
    $rateDistanceTag = 'true';
}

$request_string = htmlentities("<OTA_VehResRQ Version='1.002'>
             <POS>
    			<Source>
      				<RequestorID>
        				<CompanyName CodeContext='06110474000172'>Campos Memsch</CompanyName>
      				</RequestorID>
    			</Source>
  			</POS>
            <VehResRQCore Status='Available'>
            <RateDistance Unlimited='".$rateDistanceTag."'".$rateDistance." />
                <VehRentalCore PickUpDateTime='".$this->dataRetirada."' ReturnDateTime='".$this->dataDevolucao."'>
                    <PickUpLocation LocationCode='".$this->cidadeRetirada."'/>
                    <ReturnLocation LocationCode='".$this->cidadeDevolucao."'/>
                </VehRentalCore>
                <Customer>
                    <Primary>
                        <PersonName>
                            <GivenName>", ENT_COMPAT, "UTF-8") . $nome_completo . htmlentities("</GivenName>
                            <Surname>", ENT_COMPAT, "UTF-8") . $this->sobrenome_emissor . htmlentities("</Surname>
                        </PersonName>
                        <Telephone PhoneTechType='1' AreaCityCode='41' PhoneNumber='30839750'/>
                        <Email>suporte@carroaluguel.com</Email>
						<CustomerID ID='", ENT_COMPAT, "UTF-8"). $this->cpf_emissor . htmlentities("'/>
                        <Address>
                            <AddressLine>", ENT_COMPAT, "UTF-8"). "24 de maio, 1285" . htmlentities("</AddressLine>
                            <CityName>", ENT_COMPAT, "UTF-8") . "Curitiba" . htmlentities("</CityName>
                            <PostalCode></PostalCode>
                            <StateProv>PR</StateProv>
                        </Address>
                    </Primary>
                </Customer>
                 <VendorPref Code='" . $this->loc_rdcars . "'/>
                <VehPref Code='" . $this->tipordcar . "'/>
            </VehResRQCore>
            <VehResRQInfo>
                <ArrivalDetails TransportationCode='24'  Number='" . $this->nmb_voo . "'>
                    <OperatingCompany Code='" . $this->cia_voo . "'/>
                </ArrivalDetails>
                ".$coverage."
            </VehResRQInfo>
        </OTA_VehResRQ>", ENT_COMPAT, "UTF-8");
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <Reserve2 xmlns="http://tempuri.org/">
	<xml>
    <?php echo $request_string;?>
        </xml>
      <code>97</code>
        <observation><?php echo $obs;?></observation>
    </Reserve2>
  </soap:Body>
</soap:Envelope>