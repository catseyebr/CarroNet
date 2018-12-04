<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
	<soapenv:Header/>
	<soapenv:Body>
		<OTA_VehLocDetail xmlns="http://tempuri.org/">
			<OTA_VehLocDetailRQ RetransmissionIndicator="false" TransactionStatusCode="Start" Target="Test" TimeStamp="0001-01-01T00:00:00" EchoToken="7d235cbb-1fff-423b-838b-82610ce2d712" Version="0">
				<Location LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
			</OTA_VehLocDetailRQ>
		</OTA_VehLocDetail>
	</soapenv:Body>
</soapenv:Envelope>