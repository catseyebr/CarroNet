<?php
function normaliza($string){
    $a = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
    $b = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
    $string = utf8_decode($string);
    $string = strtr($string, utf8_decode($a), $b);
    return utf8_encode($string);
}
$unique_dado = $this->unique_id. rand(4, 5);
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <CancelaReserva xmlns="http://tempuri.org/">
            <pUsuario>maggi</pUsuario>
            <pSenha>xmlmaggi2017CA</pSenha>
            <pNumeroReserva><?php echo $this->nmbReserva; ?></pNumeroReserva>
            <pMotivo><?php echo normaliza($this->motivo); ?></pMotivo>
            <pIdentificacao>CAR2017ALU0611</pIdentificacao>
            <pNomeLocatario><?php echo strtoupper(normaliza($this->nome_emissor)); ?> <?php echo strtoupper(normaliza($this->sobrenome_emissor)); ?></pNomeLocatario>
        </CancelaReserva>
    </soap:Body>
</soap:Envelope>