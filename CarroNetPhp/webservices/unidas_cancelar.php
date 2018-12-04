<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <Usuario xmlns="http://www.unidas.com.br/">
      <Acordo>1250</Acordo>
      <Senha>0128000105</Senha>
    </Usuario>
  </soap:Header>
   <soap:Body>
    <OtaVehCancel xmlns="http://www.unidas.com.br/">
      <Ota_VehCancelRQ xmlns="http://www.opentravel.org/OTA/2003/05" Version="0">
        <VehCancelRQCore>
          <UniqueID ID="<?php echo $this->nmbReserva; ?>" />
        </VehCancelRQCore>
      </Ota_VehCancelRQ>
    </OtaVehCancel>
  </soap:Body>
</soap:Envelope>