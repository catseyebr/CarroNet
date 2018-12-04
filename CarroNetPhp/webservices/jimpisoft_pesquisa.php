<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <MultiplePrices xmlns="http://www.jimpisoft.pt/Rentway_Reservations_WS/getMultiplePrices">
            <objRequest>
                <companyCode>9930</companyCode>
                <customerCode>977</customerCode>
                <countryID></countryID>
                <city></city>
                <groupID><?php echo $this->sipp; ?></groupID>
                <rateCode></rateCode>
                <pickUp>
                    <Date><?php echo $this->dataRetirada; ?></Date>
                    <rentalStation><?php echo $this->cidadeRetirada; ?></rentalStation>
                </pickUp>
                <dropOff>
                    <Date><?php echo $this->dataDevolucao; ?></Date>
                    <rentalStation><?php echo $this->cidadeDevolucao; ?></rentalStation>
                </dropOff>
                <sessionID></sessionID>
                <username>carroaluguel</username>
                <password>carroaluguel</password>
                <onlyDynamicRate>0</onlyDynamicRate>
                <promotionCode></promotionCode>
            </objRequest>
        </MultiplePrices>
    </soap:Body>
</soap:Envelope>