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

$request_string = htmlentities("<OTA_VehResRQ xmlns='http://www.opentravel.org/OTA/2003/05' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.opentravel.org/OTA/2003/05 OTA_VehResRQ.xsd' Version='1.002'>
             <POS>
    			<Source ISOCountry='BR'>
      				<RequestorID ID='97'>
        				<CompanyName Code='CP' CodeContext='06110474000172'>Campos Mensch</CompanyName>
      				</RequestorID>
    			</Source>
  			</POS>
            <VehResRQCore Status='All'>
                <VehRentalCore PickUpDateTime='".$this->dataRetirada."' ReturnDateTime='".$this->dataDevolucao."'>
                    <PickUpLocation LocationCode='".$this->cidadeRetirada."'/>
                    <ReturnLocation LocationCode='".$this->cidadeDevolucao."'/>
                </VehRentalCore>
                <Customer>
                    <Primary>
                        <PersonName>
                            <NamePrefix>Sr(a).</NamePrefix>
                            <GivenName>", ENT_COMPAT, "UTF-8") . $this->nome_emissor . htmlentities("</GivenName>
                            <Surname>", ENT_COMPAT, "UTF-8") . $this->sobrenome_emissor . htmlentities("</Surname>
                        </PersonName>
                        <Telephone PhoneTechType='1' AreaCityCode='041' PhoneNumber='30839750'/>
                        <Email>".$this->email_emissor."</Email>
                        <Address>
                            <AddressLine>", ENT_COMPAT, "UTF-8"). $this->end_emissor . htmlentities("</AddressLine>
                            <CityName>", ENT_COMPAT, "UTF-8") . $this->cidade_emissor . htmlentities("</CityName>
                            <PostalCode>" . $this->cep_emissor . "</PostalCode>
                            <StateProv>" . $this->estado_emissor . "</StateProv>
                        </Address>
                    </Primary>
                </Customer>
                <VendorPref CompanyShortName='GMC Locadora de veiculos' Code='109'/>
                <VehPref  AirConditionInd='" . $this->arCondicionado . "' TransmissionType='" . $this->transmissao . "' >
                    <VehType VehicleCategory='" . $this->tipo . "' DoorCount='" . $this->door . "'  />
                    <VehClass Size='" . $this->categoria . "'/>
                </VehPref>
                " .
                (($this->extra_gps || $this->extra_child) ? "
                <SpecialEquipPrefs>
                    " . (($this->extra_gps) ? "<SpecialEquipPref EquipType='13' Quantity='1'/>" : "") . "
                    " . (($this->extra_child) ? "<SpecialEquipPref EquipType='8' Quantity='1'/>" : "") . "
                </SpecialEquipPrefs>" : "")
                . "
                <RateDistance Unlimited='true' DistUnitName='KM' VehiclePeriodUnitName='RentalPeriod'/>
            </VehResRQCore>
            <VehResRQInfo>
                <ArrivalDetails TransportationCode='24'  Number='" . $this->nmb_voo . "'>
                    <OperatingCompany Code='" . $this->cia_voo . "'/>
                </ArrivalDetails>
                <PricedCoverages>
                    <PricedCoverage>
                        <Coverage CoverageType='32'/>
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