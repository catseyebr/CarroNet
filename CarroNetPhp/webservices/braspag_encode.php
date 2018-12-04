<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <EncryptRequest xmlns="https://www.pagador.com.br/webservice/BraspagGeneralService">
      <merchantId>{E18C1DB2-C657-C941-54FE-0B023B7E2861}</merchantId>
      <request><?php 
			
			foreach ($this->dadosPag as $data => $value) {
				echo "<string>$data=$value</string>";
			};
			
		?></request>
    </EncryptRequest> 
  </soap:Body>
</soap:Envelope>