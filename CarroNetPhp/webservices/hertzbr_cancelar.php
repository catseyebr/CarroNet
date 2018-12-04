<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
	<soapenv:Header/>
	<soapenv:Body>
		<OTA_VehCancel xmlns="http://tempuri.org/">
			<OTA_VehCancelRQ RetransmissionIndicator="false" TransactionStatusCode="Start" Target="Test" TimeStamp="0001-01-01T00:00:00" EchoToken="7d235cbb-1fff-423b-838b-82610ce2d712" Version="0">
				<VehCancelRQCore CancelType="Book">
					<UniqueID ID="<?php echo $this->nmbReserva; ?>" Type="14" xmlns="http://www.opentravel.org/OTA/2003/05"/>
				</VehCancelRQCore>
			</OTA_VehCancelRQ>
		</OTA_VehCancel>
	</soapenv:Body>
</soapenv:Envelope>