<?php
    function normaliza($string){
        $a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
        $b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
        $string = utf8_decode($string);
        $string = strtr($string, utf8_decode($a), $b);
        return utf8_encode($string);
    }
    $coverage = '<CoveragePrefs>';
    $coverage .= '<CoveragePref CoverageType="24"/>';
    $coverage .= '</CoveragePrefs>';
    
    if($this->protecao == 1){
        $coverage = '<CoveragePrefs>';
        $coverage .= '<CoveragePref CoverageType="24"/>';
        $coverage .= '<CoveragePref CoverageType="1"/>';
        $coverage .= '</CoveragePrefs>';
    }
    $cpf = str_replace('-','',str_replace('.','',$this->cpf_emissor));
?>
<!-- Environment flag: Helps us identify in which environment this request is being processed -->
<OTA_VehResRQ Target="Production" Version="1.0" SequenceNmbr="1"
              xmlns="http://www.opentravel.org/OTA/2003/05"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <POS>
        <!-- Credentials or specific parameters of partner -->
        <Source ISOCountry="BR" ISOCurrency="BRL">
        <RequestorID Type="1" ID="Car Rental" MessagePassword="!nf0$!$t3m@$#C@rR3nt@l#2ol7"/>
        </Source>
        <Source>
        <RequestorID Type="5" ID="1"/>
        </Source>
    </POS>
    <!-- Availability types: All, Available, OnRequest -->
    <VehResRQCore Status="Available">
        <!-- Pickup and Return date and times -->
        <VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
            <!-- Pickup and Return locations (Stations) -->
            <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
            <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
        </VehRentalCore>
        <!-- Customer data -->
        <Customer>
            <Primary>
                <PersonName>
                    <GivenName><?php echo normaliza($this->nome_emissor); ?></GivenName>
                    <Surname><?php echo normaliza($this->sobrenome_emissor); ?></Surname>
                </PersonName>
                <Telephone AreaCityCode="41" PhoneNumber="123456789"/>
                <Email><?php echo $this->email_emissor; ?></Email>
                <!-- Customer document, DocType="5" = CPF (Brazilian document) -->
                <Document DocID="<?php echo $cpf; ?>" DocType="5"/>
                <CitizenCountryName Code="BR"/>
            </Primary>
        </Customer>
        <!-- Rental company data -->
        <VendorPref CompanyShortName=" Yes Franchising R1" Code="19240860000120"> Yes Franchising R1</VendorPref>
        <!-- Group data -->
        <VehPrefs>
            <VehPref Code="<?php echo $this->sipp; ?>" CodeContext="SIPP"></VehPref>
        </VehPrefs>
        <!-- Rate code and discount coupon -->
        <RateQualifier RateQualifier="<?php echo $this->rate_qual; ?>"/>
    </VehResRQCore>
    <VehResRQInfo>
        <!-- Flight details -->
        <ArrivalDetails TransportationCode="14" Number="">
            <MarketingCompany Code=""/>
        </ArrivalDetails>
        <!-- Coverages -->
        <CoveragePrefs>
            <!-- Coverage code -->
            <CoveragePref CoverageType="24"/>
        </CoveragePrefs>
        <!-- Extras -->
        <!-- When transaction uses Voucher -->
        <SpecialReqPref>CostControl={{$voucherCode}}</SpecialReqPref>
        <RentalPaymentPref>
            <Voucher Identifier="{{$voucherCode}}" ValueType="FixedValue" ElectronicIndicator="true"/>
            <PaymentAmount Amount="1490.80" CurrencyCode="BRL"/>
        </RentalPaymentPref>
    </VehResRQInfo>
</OTA_VehResRQ>
