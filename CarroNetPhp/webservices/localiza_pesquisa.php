<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
    <soapenv:Header/>
    <soapenv:Body>
        <OTA_VehAvailRate xmlns="http://tempuri.org/">
            <OTA_VehAvailRateRQ PrimaryLangID="por" RetransmissionIndicator="false" MaxPerVendorInd="false" TransactionStatusCode="Start" Target="Test" TimeStamp="0001-01-01T00:00:00" EchoToken="7d235cbb-1fff-423b-838b-82610ce2d712" Version="0">
                <POS>
                    <Source ISOCountry="BR" ISOCurrency="BRL">
                        <RequestorID ID="05915567" Type="5" xmlns="http://www.opentravel.org/OTA/2003/05"/>
                    </Source>
					<Source ISOCountry="BR" ISOCurrency="BRL">
						<RequestorID ID="97983" Type="29" xmlns="http://www.opentravel.org/OTA/2003/05"/>
					</Source>
                </POS>
                <VehAvailRQCore>
                    <VehRentalCore ReturnDateTime="<?php echo $this->dataDevolucao; ?>" PickUpDateTime="<?php echo $this->dataRetirada; ?>" xmlns="http://www.opentravel.org/OTA/2003/05">
                        <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
                        <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
                    </VehRentalCore>
                    <VehPrefs xmlns="http://www.opentravel.org/OTA/2003/05">
                        <VehPref Code="<?php echo $this->sipp; ?>"/>
                    </VehPrefs>
                </VehAvailRQCore>
				<VehAvailRQInfo>
					<CoveragePrefs xmlns="http://www.opentravel.org/OTA/2003/05">
						<CoveragePref CoverageType="7"/>
					</CoveragePrefs>
				</VehAvailRQInfo>
            </OTA_VehAvailRateRQ>
        </OTA_VehAvailRate>
    </soapenv:Body>
</soapenv:Envelope>