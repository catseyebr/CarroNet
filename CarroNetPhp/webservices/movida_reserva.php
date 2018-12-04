<?php
	function normaliza($string){
		$a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
		$b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
		$string = utf8_decode($string);
		$string = strtr($string, utf8_decode($a), $b);
		return utf8_encode($string);
	}
if($this->protecao == '1') {
	$coverage = '<CoveragePrefs>';
	$coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="24" />';
	$coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="' . $this->protecao . '" />';
	$coverage .= '</CoveragePrefs>';
}else{
	$coverage = '<CoveragePrefs>';
	$coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="' . $this->protecao . '" />';
	$coverage .= '</CoveragePrefs>';
}
$opp = NULL;
if($this->opcionais){
	$opps = explode(',',$this->opcionais);
	if(in_array('1',$opps)) {
		$opp .= '<SpecialEquipPref EquipType="13"/>';
	}
	if(in_array('2',$opps)) {
		$opp .= '<SpecialEquipPref EquipType="7"/>';
	}
	if(in_array('3',$opps)) {
		$opp .= '<SpecialEquipPref EquipType="8"/>';
	}
	if(in_array('4',$opps)) {
		$opp .= '<SpecialEquipPref EquipType="9"/>';
	}
	if(in_array('5',$opps)) {

	}
	if(in_array('6',$opps)) {
		$opp .= '<SpecialEquipPref EquipType="55"/>';
	}
}
?>
<OTA_VehResRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05     OTA_VehResRQ.xsd" Version="1.002">
	<POS>
		<Source>
		<BookingChannel Type="TOS">
			<CompanyName Code="13" CodeContext="Internal Code"></CompanyName>
		</BookingChannel>
		<RequestorID Type="5" MessagePassword="22e632ab1790cfde44607e22b7a1879e" ID="208578">
				<CompanyName>Movida</CompanyName>
			</RequestorID>
			<TPA_Extensions>
				<IM Code="67550240906" />
			</TPA_Extensions>
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
					<GivenName><?php echo normaliza($this->nome_emissor); ?></GivenName>
					<Surname><?php echo normaliza($this->sobrenome_emissor); ?></Surname>
				</PersonName>
				<Telephone PhoneTechType="1" AreaCityCode="405" PhoneNumber="5555828"/>
				<Email EmailType="2"><?php echo $this->email_emissor; ?></Email>
				<Address Type="2">
					<StreetNmbr><?php echo normaliza($this->end_emissor); ?></StreetNmbr>
					<CityName><?php echo normaliza($this->cidade_emissor); ?></CityName>
					<PostalCode><?php echo $this->cep_emissor; ?></PostalCode>
					<StateProv><?php echo $this->estado_emissor; ?></StateProv>
				</Address>
			</Primary>
		</Customer>
		<VehPref Code="<?php echo $this->sipp; ?>" CodeContext="ACRISS"></VehPref>
		<?php echo $opp; ?>
		<RateQualifier RateQualifier="<?php echo $this->rate_qual; ?>" PromotionCode="<?php echo $this->promocod; ?>"></RateQualifier>
	</VehResRQCore>
	<VehResRQInfo>
		<?php echo $coverage; ?>
		<ArrivalDetails TransportationCode="14" Number="<?php echo $this->nmb_voo ?>">
			<OperatingCompany Code="<?php echo $this->cia_voo ?>"/>
		</ArrivalDetails>
	</VehResRQInfo>
</OTA_VehResRQ>
