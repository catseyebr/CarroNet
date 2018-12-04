<?php
$coverage = '<CoveragePrefs>';
$coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="'.$this->protecao.'" />';
$coverage .= '</CoveragePrefs>';
if($this->protecao == 50){
	$coverage = '<CoveragePrefs>';
	$coverage .= '<CoveragePref CoverageType="30" PreferLevelField="Only"/>';
	$coverage .= '<CoveragePref CoverageType="50" PreferLevelField="Only"/>';
	$coverage .= '</CoveragePrefs>';
}
if($this->protecao == 51){
	$coverage = '<CoveragePrefs>';
	$coverage .= '<CoveragePref CoverageType="41" PreferLevelField="Only"/>';
	$coverage .= '<CoveragePref CoverageType="50" PreferLevelField="Only"/>';
	$coverage .= '</CoveragePrefs>';
}
if($this->protecao == 61){
    $coverage = '<CoveragePrefs>';
    $coverage .= '<CoveragePref CoverageType="30" PreferLevelField="Only"/>';
    $coverage .= '<CoveragePref CoverageType="61" PreferLevelField="Only"/>';
    $coverage .= '</CoveragePrefs>';
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header>
		<Usuario xmlns="http://www.unidas.com.br/">
			<Acordo>1250</Acordo>
			<Senha>0128000105</Senha>
		</Usuario>
	</soap:Header>
	<soap:Body>
    	<OtaVehRes xmlns="http://www.unidas.com.br/">
      		<OTA_VehResRQ xmlns="http://www.opentravel.org/OTA/2003/05" Version="0">
        		<VehResRQCore Status="All">
          			<VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
            			<PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
            			<ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
          			</VehRentalCore>
                    <VehPref Code="<?php echo $this->sipp; ?>" />
                    <Customer>
                        <Primary>
                            <PersonName>
                                <GivenName><?php echo $this->nome_emissor." ".$this->sobrenome_emissor; ?></GivenName>
                            </PersonName>
                            <Telephone PhoneTechType="1" AreaCityCode="<?php echo $this->ddd_emissor; ?>" PhoneNumber="<?php echo $this->fone_emissor; ?>"/>
                            <Email EmailType="2"><?php echo $this->email_emissor; ?></Email>
                            <Address Type="2">
                                <AddressLine><?php echo $this->end_emissor; ?></AddressLine>
                                <CityName><?php echo $this->cidade_emissor; ?></CityName>
                                <PostalCode><?php echo $this->cep_emissor; ?></PostalCode>
                                <StateProv><?php echo $this->estado_emissor; ?></StateProv>
                            </Address>
                            <CitizenCountryName Code="BR"/>
                            <CustLoyalty ProgramID="RESERVE_GANHE" MembershipID="60807334987" />
                        </Primary>
                    </Customer>
					<RateQualifier RateQualifier="<?php echo $this->rate_qual; ?>"/>
        		</VehResRQCore>
        		<VehResRQInfo>
          			<?php echo $coverage; ?>
                    <?php if($this->cia_voo != "" && $this->nmb_voo != ""){?>
                    <ArrivalDetails TransportationCode="14" Number="<?php echo $this->nmb_voo ?>">
				<OperatingCompany Code="<?php echo $this->cia_voo ?>"/>
            </ArrivalDetails>
            		<?php } ?>
        		</VehResRQInfo>
      		</OTA_VehResRQ>
    	</OtaVehRes>
  	</soap:Body>
</soap:Envelope>