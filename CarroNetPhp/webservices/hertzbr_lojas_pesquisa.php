<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/" xmlns:ns="http://www.opentravel.org/OTA/2003/05">
	<soapenv:Header/>
	<soapenv:Body>
		<OTA_VehLocSearch xmlns="http://tempuri.org/">
			<OTA_VehLocSearchRQ RetransmissionIndicator="false" JustAddressPhone="false" TransactionStatusCode="Start" Target="Test" TimeStamp="0001-01-01T00:00:00" EchoToken="7d235cbb-1fff-423b-838b-82610ce2d712" Version="0">
				<VehLocSearchCriterion>
					<Address xmlns="http://www.opentravel.org/OTA/2003/05">
						<CityName><?php echo $this->nomeCidade; ?></CityName>
						<CountryName Code="BR"/>
					</Address>
				</VehLocSearchCriterion>
			</OTA_VehLocSearchRQ>
		</OTA_VehLocSearch>
	</soapenv:Body>
</soapenv:Envelope>