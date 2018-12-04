<?php
if (!function_exists(normaliza)){
	function normaliza($string){
		$a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
		$b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
		$string = utf8_decode($string);
		$string = strtr($string, utf8_decode($a), $b);
		return utf8_encode($string);
}
}
if($this->ismundipagg){
    $var_mundipag = 'B';
}else{
    $var_mundipag = 'A';
}
?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
	<soapenv:Header/>
	<soapenv:Body>
		<OTA_VehRes xmlns="http://tempuri.org/">
			<OTA_VehResRQ RetransmissionIndicator="false" TransactionStatusCode="Start" Target="Test" TimeStamp="<?php echo $this->date_timestamp; ?>" EchoToken="7d235cbb-1fff-423b-838b-82610ce2d712" Version="0">
				<POS>
					<Source ISOCountry="BR" ISOCurrency="BRL">
						<RequestorID ID="09345353" Type="5" xmlns="http://www.opentravel.org/OTA/2003/05"/>
					</Source>
					<Source ISOCountry="BR" ISOCurrency="BRL">
						<RequestorID ID="97983" Type="29" xmlns="http://www.opentravel.org/OTA/2003/05"/>
					</Source>
				</POS>
				<VehResRQCore>
					<UniqueID ID="<?php echo $this->unique_id; ?>" Type="24" xmlns="http://www.opentravel.org/OTA/2003/05"/>
					<VehRentalCore OneWayIndicator="true" ReturnDateTime="<?php echo $this->dataDevolucao; ?>" PickUpDateTime="<?php echo $this->dataRetirada; ?>" xmlns="http://www.opentravel.org/OTA/2003/05">
						<PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>" CodeContext="internal code"/>
						<ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>" CodeContext="internal code"/>
					</VehRentalCore>
					<Customer xmlns="http://www.opentravel.org/OTA/2003/05">
						<Primary>
							<PersonName>
								<GivenName><?php echo normaliza($this->nome_emissor); ?></GivenName>
								<MiddleName></MiddleName>
								<Surname><?php echo normaliza($this->sobrenome_emissor); ?></Surname>
							</PersonName>
							<Telephone PhoneTechType="1" CountryAccessCode="55" AreaCityCode="31" PhoneNumber="<?php echo $this->fone_emissor; ?>"/>
							<Email ValidInd="true"><?php echo $this->email_emissor; ?></Email>
                            <Address Type="2">
                                <StreetNmbr><?php echo $var_mundipag; ?></StreetNmbr>
                                <CityName></CityName>
                                <PostalCode></PostalCode>
                                <StateProv></StateProv>
                            </Address>
							<CitizenCountryName Code="BR"/>
							<Document DocID="<?php echo $this->cpf_emissor; ?>" DocType="<?php echo $this->passe_emissor; ?>"/>
						</Primary>
					</Customer>
					<VehPref FuelType="Unspecified" DriveType="Unspecified" AirConditionInd="<?php echo reset($this->arr_dados['vendoravail'])['AirConditionInd']; ?>" TransmissionType="<?php echo reset($this->arr_dados['vendoravail'])['TransmissionType']; ?>" CodeContext="internal code" Code="<?php echo reset($this->arr_dados['vendoravail'])['Code']; ?>" xmlns="http://www.opentravel.org/OTA/2003/05">
						<VehType VehicleCategory="<?php echo reset($this->arr_dados['vendoravail'])['VehicleCategory']; ?>" DoorCount="<?php echo reset($this->arr_dados['vendoravail'])['DoorCount']; ?>"/>
						<VehClass Size="<?php echo reset($this->arr_dados['vendoravail'])['Size']; ?>"/>
					</VehPref>
					<RateQualifier RateQualifier="<?php echo reset($this->arr_dados['vendoravail'])['Rate_RateQualifier']; ?>" RateCategory="<?php echo reset($this->arr_dados['vendoravail'])['Rate_RateCategory']; ?>" xmlns="http://www.opentravel.org/OTA/2003/05"/>
				</VehResRQCore>
				<VehResRQInfo>
					<CoveragePrefs xmlns="http://www.opentravel.org/OTA/2003/05">
						<CoveragePref CoverageType="7"/>
					</CoveragePrefs>
					<RentalPaymentPref PaymentType="1" xmlns="http://www.opentravel.org/OTA/2003/05"/>
					<Reference ID="<?php echo reset($this->arr_dados['vendoravail'])['References_ID']; ?>" Type="<?php echo reset($this->arr_dados['vendoravail'])['References_Type']; ?>" xmlns="http://www.opentravel.org/OTA/2003/05"/>
				</VehResRQInfo>
                <TourInfo TourNumber="<?php echo $var_mundipag; ?>"></TourInfo>
			</OTA_VehResRQ>
		</OTA_VehRes>
	</soapenv:Body>
</soapenv:Envelope>