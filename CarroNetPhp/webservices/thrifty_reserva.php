<?php 
$ar_cod = ($this->arCondicionado)?"S":"N";
$transm = ($this->transmissao == "Automatic")?"A":"M";
?>
<OTA_VehResRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehResRQ.xsd" Version="1.002">
	<POS>
	<Source ISOCountry="BR" AgentDutyCode="513896">
		<RequestorID Type="4" ID="40358">
	<CompanyName>Layum Travel</CompanyName>
	</RequestorID>
	</Source>
	</POS>
	<VehResRQCore Status="All">
		<VehRentalCore PickUpDateTime="<?php echo $this->dataRetirada; ?>" ReturnDateTime="<?php echo $this->dataDevolucao; ?>">
			<PickUpLocation LocationCode="<?php echo $this->cidadeRetirada; ?>"/>
			<ReturnLocation LocationCode="<?php echo $this->cidadeDevolucao; ?>"/>
		</VehRentalCore>
		<Customer>
			<Primary>
				<PersonName>
					<GivenName><?php echo $this->nome_emissor; ?></GivenName>
					<Surname><?php echo $this->sobrenome_emissor; ?></Surname>
				</PersonName>
				<Telephone PhoneTechType="1" AreaCityCode="405" PhoneNumber="5555828"/>
				<Email EmailType="2"><?php echo $this->email_emissor; ?></Email>
				<Address Type="2">
					<StreetNmbr><?php echo $this->end_emissor; ?></StreetNmbr>
					<CityName><?php echo $this->cidade_emissor; ?></CityName>
					<PostalCode><?php echo $this->cep_emissor; ?></PostalCode>
					<StateProv><?php echo $this->estado_emissor; ?></StateProv>
				</Address>
			</Primary>
		</Customer>
		<VendorPref CompanyShortName="<?php echo $this->locadora_short; ?>" Code="ZE" PreferLevel="Preferred"/>
		<VehPref AirConditionInd="<?php echo $ar_cod; ?>" TransmissionType="<?php echo $transm; ?>">
			<VehType VehicleCategory="<?php echo $this->tipo; ?>" DoorCount="<?php echo $this->door; ?>"/>
			<VehClass Size="<?php echo $this->categoria; ?>"/>
		</VehPref>
	</VehResRQCore>
	<VehResRQInfo>
		<ArrivalDetails TransportationCode="14" Number="<?php echo $this->nmb_voo ?>">
			<OperatingCompany Code="<?php echo $this->cia_voo ?>"/>
		</ArrivalDetails>
	<RentalPaymentPref>
			<PaymentCard CardType="1" CardCode="" CardNumber="" ExpireDate="">
				<CardHolderName></CardHolderName>
			</PaymentCard>
		</RentalPaymentPref>
	</VehResRQInfo>
</OTA_VehResRQ>