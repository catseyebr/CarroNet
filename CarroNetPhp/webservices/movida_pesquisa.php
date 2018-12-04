<?php
if($this->protecao) {
    if ($this->protecao == '1') {
        $coverage = '<CoveragePrefs>';
        $coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="24" />';
        $coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="' . $this->protecao . '" />';
        $coverage .= '</CoveragePrefs>';
    } else {
        $coverage = '<CoveragePrefs>';
        $coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="' . $this->protecao . '" />';
        $coverage .= '</CoveragePrefs>';
    }
}else{
    $coverage = '<CoveragePrefs>';
    $coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="24" />';
    $coverage .= '</CoveragePrefs>';
}

if($this->nmb_diarias == '30' && $this->protecao != '11'){
    $coverage = '<CoveragePrefs>';
    $coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="24" />';
    $coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="1" />';
    $coverage .= '</CoveragePrefs>';
}


if($this->cidadeRetirada){
	$rate = '';
}else{
	$rate = '';
}
if($this->sipp) {
    $sipp = '<VehPref CodeContext="ACRISS" Code="' . $this->sipp . '"/>';
}
$opp = NULL;
if($this->opcionais){
	$opps = explode(',',$this->opcionais);
	$opp .= '<SpecialEquipPrefs>';
	if(in_array('1',$opps)) {
		$opp .= '<SpecialEquipPref EquipType="13"/>';
	}
	if(in_array('2',$opps)) {
		$opp .= '<SpecialEquipPref EquipType="7"/>';
	}
	if(in_array('3',$opps)) {
		$opp .= '<SpecialEquipPref EquipType="8"/>';
		//$opp .= '<SpecialEquipPref EquipType="8" Quantity="2"/>';
	}
	if(in_array('4',$opps)) {
		$opp .= '<SpecialEquipPref EquipType="9"/>';
	}
	if(in_array('5',$opps)) {

	}
	if(in_array('6',$opps)) {
		$opp .= '<SpecialEquipPref EquipType="55"/>';
	}
    $opp .= '</SpecialEquipPrefs>';

}
?>
<OTA_VehAvailRateRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehAvailRateRQ.xsd" Version="1.002">
	<POS>
		<Source>
		<BookingChannel Type="TOS">
			<CompanyName Code="13" CodeContext="Internal Code"></CompanyName>
		</BookingChannel>
		<RequestorID Type="5" MessagePassword="22e632ab1790cfde44607e22b7a1879e" ID="208578">
			<CompanyName>Movida</CompanyName>
		</RequestorID>
		</Source>
	</POS>
	<VehAvailRQCore Status="Available">
		<VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
			<PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"></PickUpLocation>
			<ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"></ReturnLocation>
		</VehRentalCore>
		<VehPrefs>
            <?php echo $sipp; ?>

		</VehPrefs>
        <?php echo $opp; ?>
		<RateQualifier RateQualifier="" PromotionCode="<?php echo $this->promocod; ?>"></RateQualifier>
	</VehAvailRQCore>
	<VehAvailRQInfo>
		<?php echo $coverage; ?>
	</VehAvailRQInfo>
</OTA_VehAvailRateRQ>