<?php
$request_string = '<OTA_HotelSearchRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 http://www.opentravel.org/2004B/OTA_HotelSearchRQ.xsd" Version="1.0" EchoToken="1234" TimeStamp="2004-01-01T08:00:00" Target="Test" PrimaryLangID="pt-BR">
	<POS>
		<Source>
			<RequestorID Type="4" ID="465394" URL="http://www.carroaluguel.com"/>
		</Source>
	</POS>
	<Criteria>
		<Criterion ImportanceType="Mandatory">
			<HotelRef HotelCityCode="CWB"/>
		</Criterion>
	</Criteria>
</OTA_HotelSearchRQ>
';
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header>
    	<PayloadInfo Username="PALAYUM" Password="PAL%AYU" IDParceiro="465394" xmlns="http://www.cmnet/xmlwebservices2/" />
    </soap:Header>
    <soap:Body>
    	<xmlConsultaHoteis xmlns="http://www.cmnet/xmlwebservices2/">
        	<Xml><?php echo $request_string;?></Xml>
        </xmlConsultaHoteis>
	</soap:Body>
</soap:Envelope>