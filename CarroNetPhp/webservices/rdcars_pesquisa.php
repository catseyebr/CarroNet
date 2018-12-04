<?php
    $opp = NULL;
    $opp_arr = NULL;
    if($this->opcionais){
        $opps = explode(',',$this->opcionais);
        if(in_array('1',$opps)) {
            $opp_arr[] = '13';
        }
        if(in_array('2',$opps)) {
            $opp_arr[] = '7';
        }
        if(in_array('3',$opps)) {
            $opp_arr[] = '8';
        }
        if(in_array('4',$opps)) {
            $opp_arr[] = '9';
        }
        if(in_array('5',$opps)) {
            $opp_arr[] = '601';
        }
    }
    if(is_array($opp_arr)){
        $opp = implode(',',$opp_arr);
    }
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <RentalSearchEquipTypes xmlns="http://tempuri.org/">
      <withdrawalLocation><?php echo $this->cidadeRetirada; ?></withdrawalLocation>
      <deliveryLocation><?php echo $this->cidadeDevolucao; ?></deliveryLocation>
      <startDate><?php echo $this->dataRetirada; ?></startDate>
      <endDate><?php echo $this->dataDevolucao; ?></endDate>
      <code>97</code>
      <groupsCodes><?php echo $this->tipordcar;?></groupsCodes>
      <rentalCode><?php echo $this->loc; ?></rentalCode>
      <equipTypesCodes><?php echo $opp; ?></equipTypesCodes>
    </RentalSearchEquipTypes>
  </soap:Body>
</soap:Envelope>