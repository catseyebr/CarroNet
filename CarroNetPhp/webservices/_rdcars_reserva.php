<?php 
if (!function_exists("t2d")) {
function t2d($hora){ 
    $hora_aux = explode(':', $hora);
	$h = ($hora_aux[0] . '.' . ($hora_aux[1] * 100 / 60));	
    return $h;
}}


$dtareti = split("T",$this->dataRetirada); 
$dta1 = split("-",$dtareti[0]);
$dtaRetirada = $dta1[1]."-".$dta1[2]."-".$dta1[0];
$horaRetirada = t2d($dtareti[1]);

$dtadevo = split("T",$this->dataDevolucao); 
$dta2 = split("-",$dtadevo[0]);
$dtaDevolucao = $dta2[1]."-".$dta2[2]."-".$dta2[0];
$horaDevolucao = t2d($dtadevo[1]);

$fone = split('-',$this->fone_emissor);
$f_ddd = $fone[0];
$f_nmb = $fone[1];

$request_string = htmlentities("<OTA_VehResRQ Version='1.002'>
             <POS>
    			<Source>
      				<RequestorID>
        				<CompanyName CodeContext='06110474000172'>Campos Memsch</CompanyName>
      				</RequestorID>
    			</Source>
  			</POS>
            <VehResRQCore Status='Available'>
                <VehRentalCore PickUpDateTime='".$this->dataRetirada."' ReturnDateTime='".$this->dataDevolucao."'>
                    <PickUpLocation LocationCode='".$this->cidadeRetirada."'/>
                    <ReturnLocation LocationCode='".$this->cidadeDevolucao."'/>
                </VehRentalCore>
                <Customer>
                    <Primary>
                        <PersonName>
                            <GivenName>", ENT_COMPAT, "UTF-8") . $this->nome_emissor . htmlentities("</GivenName>
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
                <VehPref  AirConditionInd='" . $this->arCondicionado . "' TransmissionType='" . $this->transmissao . "' >
                    <VehType VehicleCategory='" . $this->tipo . "' DoorCount='" . $this->door . "'  />
                    <VehClass Size='" . $this->categoria . "'/>
                </VehPref>
                <RateDistance Unlimited='true' DistUnitName='KM' VehiclePeriodUnitName='RentalPeriod'/>
            </VehResRQCore>
            <VehResRQInfo>
                <ArrivalDetails TransportationCode='24'  Number='" . $this->nmb_voo . "'>
                    <OperatingCompany Code='" . $this->cia_voo . "'/>
                </ArrivalDetails>
                <PricedCoverages>
                    <PricedCoverage>
                        <Coverage CoverageType='".$this->protecao."'/>
                    </PricedCoverage>
                </PricedCoverages>
            </VehResRQInfo>
        </OTA_VehResRQ>", ENT_COMPAT, "UTF-8");
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <Reserve xmlns="http://tempuri.org/">
	<xml>
    <?php echo $request_string;?>		
        </xml>
      <code>97</code>
    </Reserve>
  </soap:Body>
</soap:Envelope>