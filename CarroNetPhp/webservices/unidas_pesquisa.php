<?php
    $coverage = '<CoveragePrefs>';
    $coverage .= '<CoveragePref PreferLevelField="Only" CoverageType="ALL" />';
    $coverage .= '</CoveragePrefs>';
    if ($this->cidadeRetirada) {
        $rate = '';
    } else {
        $rate = '';
    }
    if ($this->ratequal != '') {
        $ratequalifier = $this->ratequal;
    } else {
        $ratequalifier = 136692;
        //$ratequalifier = 128677;
    }
    if($this->promocod == 'AA058875'){
        $ratequalifier = 136901;
        //$ratequalifier = 131793;
        $this->promocod = NULL;
    }else if($this->promocod == 'BLACKOP'){
        $ratequalifier = 130655;
        $this->promocod = NULL;
    }else if($this->promocod == 'BLACKCLI'){
        $ratequalifier = 130652;
        $this->promocod = NULL;
    }
    $opp = null;
    if ($this->opcionais) {
        $opps = explode(',', $this->opcionais);
        if (in_array('1', $opps)) {
            $opp .= '<SpecialEquipPref EquipType="13"/>';
        }
        if (in_array('2', $opps)) {
            $opp .= '<SpecialEquipPref EquipType="7"/>';
        }
        if (in_array('3', $opps)) {
            $opp .= '<SpecialEquipPref EquipType="8"/>';
        }
        if (in_array('4', $opps)) {
            $opp .= '<SpecialEquipPref EquipType="9"/>';
        }
        if (in_array('5', $opps)) {
            $opp .= '<SpecialEquipPref EquipType="91"/>';
        }
        if (in_array('6', $opps)) {
            $opp .= '<SpecialEquipPref EquipType="55"/>';
        }
    }
?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <soap:Header>
        <Usuario xmlns="http://www.unidas.com.br/">
            <Acordo>1250</Acordo>
            <Senha>0128000105</Senha>
        </Usuario>
    </soap:Header>
    <soap:Body>
        <OtaVehAvailRate xmlns="http://www.unidas.com.br/">
            <OTA_VehAvailRateRQ Version="0" xmlns="http://www.opentravel.org/OTA/2003/05">
                <VehAvailRQCore Status="Available">
                    <VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>"
                                   ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
                        <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
                        <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
                    </VehRentalCore>
                    <VehPrefs>
                        <VehPref Code="<?php echo $this->sipp; ?>"/>
                    </VehPrefs>
                    <?php echo $opp; ?>
                    <RateQualifier RateQualifier="<?php echo $ratequalifier; ?>"/>
                </VehAvailRQCore>
                <VehAvailRQInfo>
                    <?php echo $coverage; ?>
                </VehAvailRQInfo>
            </OTA_VehAvailRateRQ>
        </OtaVehAvailRate>
    </soap:Body>
</soap:Envelope>