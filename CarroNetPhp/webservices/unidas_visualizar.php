<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <Usuario xmlns="http://www.unidas.com.br/">
      <Acordo>1250</Acordo>
      <Senha>0128000105</Senha>
    </Usuario>
  </soap:Header>
  <soap:Body>
    <OtaVehRetRes xmlns="http://www.unidas.com.br/">
      <OTA_VehRetResRQ xmlns="http://www.opentravel.org/OTA/2003/05" Version="0">
        <VehRetResRQCore/>
        <VehRetResRQInfo>
          <PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>" />
          <ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>" />
          <SearchDateRange Start="<?php echo $this->dataRetirada; ?>" End="<?php echo $this->dataDevolucao; ?>" />
        </VehRetResRQInfo>
      </OTA_VehRetResRQ>
    </OtaVehRetRes>
  </soap:Body>
</soap:Envelope>