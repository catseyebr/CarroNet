<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <GroupDetails xmlns="http://www.jimpisoft.pt/Rentway_Reservations_WS/getGroupDetails">
            <objRequest>
                <companyCode>9930</companyCode>
                <groupID><?php echo $this->sipp; ?></groupID>
            </objRequest>
        </GroupDetails>
    </soap:Body>
</soap:Envelope>