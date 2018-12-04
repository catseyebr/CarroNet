<?php
function normaliza($string){
$a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
$b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
$string = utf8_decode($string);
$string = strtr($string, utf8_decode($a), $b);
return utf8_encode($string);
}
?>
<OTA_VehRetResRQ EchoToken="" Version="1.002" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehRetResRQ.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.opentravel.org/OTA/2003/05">
	<POS>
		<Source>
		<BookingChannel Type="TOS">
			<CompanyName Code="13" CodeContext="Internal Code"></CompanyName>
		</BookingChannel>
		<RequestorID Type="5" MessagePassword="22e632ab1790cfde44607e22b7a1879e" ID="208578">
				<CompanyName>Movida</CompanyName>
			</RequestorID>
		</Source>
	</POS>
	<VehRetResRQCore>
		<UniqueID Type="14" ID="<?php echo $this->nmbReserva; ?>"/>
		<PersonName>
			<Surname><?php echo normaliza($this->sobrenome_emissor); ?></Surname>
		</PersonName>
	</VehRetResRQCore>
</OTA_VehRetResRQ>