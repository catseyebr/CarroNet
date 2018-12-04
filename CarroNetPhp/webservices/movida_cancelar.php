<?php
function normaliza($string){
$a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
$b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
$string = utf8_decode($string);
$string = strtr($string, utf8_decode($a), $b);
return utf8_encode($string);
}
?>
<OTA_VehCancelRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehCancelRQ.xsd" Version="1.002">
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
	<VehCancelRQCore CancelType="Book">
		<UniqueID Type="8" ID="<?php echo $this->nmbReserva; ?>"></UniqueID>
	</VehCancelRQCore>
</OTA_VehCancelRQ>

