<?php
    $request_string = htmlentities('<OTA_VehRetResRQ>
			<POS>
                <Source>
                    <RequestorID>
                        <CompanyName CodeContext="06110474000172">Campos Memsch</CompanyName>
                    </RequestorID>
                </Source>
                </POS>
				<VehRetResRQCore>
					<UniqueID ID="' . $this->nmbReserva . '"/>
				</VehRetResRQCore>
				<VehRetResRQInfo>
                    <Vendor Code="' . $this->loc_rdcars . '" />
                </VehRetResRQInfo>
			</OTA_VehRetResRQ>', ENT_COMPAT, 'UTF-8');
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <ViewReserveAutoCurrency2 xmlns="http://tempuri.org/">
            <code>97</code>
            <xml><?php echo $request_string; ?></xml>
        </ViewReserveAutoCurrency2>
    </soap:Body>
</soap:Envelope>