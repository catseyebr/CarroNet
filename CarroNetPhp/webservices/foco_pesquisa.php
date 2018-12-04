<Request xmlns="http://www.thermeon.com/webXG/xml/webxml/" referenceNumber="<?php echo mktime((int)date('h'), (int)date('i'), (int)date('s'), (int)date('m'), (int)date('d'), (int)date('y'));?>" version="2.2700">
  <ResRates>
    <Pickup locationCode="<?php echo $this->cidadeRetirada; ?>" dateTime="<?php echo $this->dataRetirada; ?>"/>
    <Return locationCode="<?php echo $this->cidadeDevolucao; ?>" dateTime="<?php echo $this->dataDevolucao; ?>"/>
    <Class><?php echo $this->sipp; ?></Class>
    <CorpRateID>LAYUM</CorpRateID>
    <EstimateType>3</EstimateType>
  </ResRates>
</Request>