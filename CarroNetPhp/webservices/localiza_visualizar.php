<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
	<soapenv:Header/>
	<soapenv:Body>
		<OTA_VehRetRes xmlns="http://tempuri.org/">
			<OTA_VehRetResRQ RetransmissionIndicator="false" TransactionStatusCode="Start" Target="Test" TimeStamp="0001-01-01T00:00:00" EchoToken="2bf68df7-e0ba-483b-bceb-b3319080fa37" Version="0">
				<VehRetResRQCore>
					<UniqueID ID="<?php echo $this->nmbReserva; ?>" Type="14" xmlns="http://www.opentravel.org/OTA/2003/05"/>
				</VehRetResRQCore>
			</OTA_VehRetResRQ>
		</OTA_VehRetRes>
	</soapenv:Body>
</soapenv:Envelope>