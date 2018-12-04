<?php
$request_string = '<CMNET_HotelInfoRQ Version="1.0" EchoToken="12345678" Target="Test" TimeStamp="2005-06-20T80:00:00" PrimaryLangID="pt-BR" xmlns="http://www.cmnet/xmlwebservices2/">
	<HotelInfos>
		<HotelInfo HotelCodeID="61867">
			<InfoHotel SendEndereco="true" SendInfoPropriedade="true" SendServicos="true"/>
			<InfoFacilidades SendSalaReuniao="true" SendRestaurantes="true"/>
			<InfoPoliticas SendPoliticas="true"/>
			<InfoAreas SendPontosProximos="true" SendTransportes="true" SendDirecoesCaminhos="true"/>
			<InfoAfiliacoes SendSistemaDistribuicao="false" SendMarcasAfiliadas="false" SendProgramaFidelidade="false" SendPremios="false"/>
			<InfoMultimidia SendGaleriaFotos="true"/>
		</HotelInfo>
	</HotelInfos>
</CMNET_HotelInfoRQ>
';
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header>
    	<PayloadInfo Username="PALAYUM" Password="PAL%AYU" IDParceiro="465394" xmlns="http://www.cmnet/xmlwebservices2/" />
    </soap:Header>
    <soap:Body>
    	<xmlRetornaInfoHotel xmlns="http://www.cmnet/xmlwebservices2/">
        	<Xml><?php echo $request_string;?></Xml>
        </xmlRetornaInfoHotel>
	</soap:Body>
</soap:Envelope>