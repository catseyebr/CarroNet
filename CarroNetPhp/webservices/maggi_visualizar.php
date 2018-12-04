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
        <PesquisaReserva xmlns="http://tempuri.org/">
            <pUsuario>maggi</pUsuario>
            <pSenha>xmlmaggi2017CA</pSenha>
            <pNomeLocatario><?php echo normaliza($this->nome_emissor); ?> <?php echo normaliza($this->sobrenome_emissor); ?></pNomeLocatario>
            <pNumeroReserva><?php echo $this->nmbReserva; ?></pNumeroReserva>
            <pIdentificacao>CAR2017ALU0611</pIdentificacao>
        </PesquisaReserva>
    </soap:Body>
</soap:Envelope>