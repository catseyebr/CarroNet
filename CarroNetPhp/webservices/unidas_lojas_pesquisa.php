
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header>
		<Usuario xmlns="http://www.unidas.com.br/">
			<Acordo>1250</Acordo>
			<Senha>0128000105</Senha>
		</Usuario>
	</soap:Header>
	<soap:Body>
		<OtaVehLocSearch xmlns="http://www.unidas.com.br/">
			<OTA_VehLocSearchRQ EchoToken="string" TimeStamp="string" Version="string" Target="string" SequenceNmbr="string" ReqRespVersion="string" xmlns="http://www.opentravel.org/OTA/2003/05">
				<VehLocSearchCriterion ExactMatch="string" ImportanceType="string">
					<RefPoint>*</RefPoint>
				</VehLocSearchCriterion>
			</OTA_VehLocSearchRQ>
		</OtaVehLocSearch>
	</soap:Body>
</soap:Envelope>