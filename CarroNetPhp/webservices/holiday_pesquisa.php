<?php
$requestString = htmlentities("
	<request>
		<requestHeader userId='LAYUM' password='LAYUM' requestType='searchLocations' language='pt' currency='BRL'/>
		<requestBody searchName='".$this->cidadeRetirada."'/>
	</request>
");
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
	<MTServiceRequestString xmlns="http://tempuri.org/">
    	<myRequest><?php echo $requestString; ?></myRequest>
    </MTServiceRequestString>
  </soap:Body>
</soap:Envelope>