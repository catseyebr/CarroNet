<!-- Environment flag: Helps us identify in which environment this request is being processed -->
<OTA_VehAvailRateRQ Target="Production" Version="1.0" SequenceNmbr="1"
                    MaxResponses="100" xmlns="http://www.opentravel.org/OTA/2003/05"
                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <POS>
        <!-- Credentials or specific parameters of partner -->
        <Source ISOCountry="BR" ISOCurrency="BRL">
        <RequestorID Type="1" ID="CarroAluguel" MessagePassword="!nf0$!$t3m@$#C@rR3nt@l#2ol7"/>
        </Source>
        <Source>
        <RequestorID Type="5" ID="1"/>
        </Source>
    </POS>
    <!-- Availability types: All, Available, OnRequest -->
    <VehAvailRQCore Status="Available">
        <!-- Pickup and Return date and times -->
        <VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>"
                       ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
            <!-- Pickup and Return locations (Stations) -->
            <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
            <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
        </VehRentalCore>
        <!-- Rental company data -->
        <VendorPref CompanyShortName="Yes Franchising R1" Code="00338314000107">Yes Franchising R1</VendorPref>
        <VehPrefs>
            <!-- Group code  -->
            <VehPref Code="<?php echo $this->sipp; ?>"/>
        </VehPrefs>
        <!-- Rate code and discount coupon -->
        <RateQualifier RateQualifier="" CorpDiscountNmbr=""/>
        <!-- Coverages -->
        <CoveragePrefs>
            <!-- Coverage code -->
            <CoveragePref CoverageType="24"/>
        </CoveragePrefs>
        <!-- Extras -->
    </VehAvailRQCore>
</OTA_VehAvailRateRQ>