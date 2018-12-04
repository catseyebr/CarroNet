<?php
$request_string = htmlentities('<OTA_VehCancelRQ Version="1.002">
    <POS>
        <Source>
            <RequestorID>
                <CompanyName CodeContext="06110474000172">Campos Memsch</CompanyName>
            </RequestorID>
        </Source>
    </POS>
    <VehCancelRQCore>
        <UniqueID ID="'.$this->nmbReserva.'" />
    </VehCancelRQCore>
    <VehCancelRQInfo>
        <Vendor Code="' . $this->loc_rdcars . '" />
    </VehCancelRQInfo>
</OTA_VehCancelRQ>', ENT_COMPAT, 'UTF-8');
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Body>
		<CancelReserve xmlns="http://tempuri.org/">
			<code>97</code>
			<xml><?php echo $request_string;?>	</xml>
		</CancelReserve>
	</soap:Body>
</soap:Envelope>