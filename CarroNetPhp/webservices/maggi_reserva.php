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
        <GravaReserva xmlns="http://tempuri.org/">
            <pUsuario>maggi</pUsuario>
            <pSenha>xmlmaggi2017CA</pSenha>
            <pDataRetirada><?php echo $this->dataRetirada; ?></pDataRetirada>
            <pDataRetorno><?php echo $this->dataDevolucao; ?></pDataRetorno>
            <pIdLocalRetirada><?php echo $this->cidadeRetirada; ?></pIdLocalRetirada>
            <pIdLocalRetorno><?php echo $this->cidadeDevolucao; ?></pIdLocalRetorno>
            <pIdTarifa><?php echo $this->rate_qual; ?></pIdTarifa>
            <pGrupoVeiculo><?php echo $this->sipp; ?></pGrupoVeiculo>
            <pNome><?php echo normaliza(substr($this->nome_emissor,0,12)); ?> <?php echo normaliza(substr($this->sobrenome_emissor,0,18)); ?></pNome>
            <pCpf><?php if($this->cpf_emissor != ''){echo $this->cpf_emissor;} ?></pCpf>
            <pEmail><?php echo $this->email_emissor; ?></pEmail>
            <pTelefone><?php echo str_replace(' ','',str_replace('-','',str_replace(')','',str_replace('(','',$this->fone_emissor)))); ?></pTelefone>
            <pNumeroCNH></pNumeroCNH>
            <pEstrangeiro>N</pEstrangeiro>
            <pPaisOrigem></pPaisOrigem>
            <pNumeroPassaporte></pNumeroPassaporte>
            <pObservacao></pObservacao>
            <pIdsProtecao>25</pIdsProtecao>
            <pIdsOpcionais></pIdsOpcionais>
            <pIdsTaxas>29</pIdsTaxas>
            <pIdentificacao>CAR2017ALU0611</pIdentificacao>
            <pUsuarioReserva>web@layum.com</pUsuarioReserva>
            <pSenhaReserva>Layum0602</pSenhaReserva>
            <pNumeroVoucher><?php echo $this->nmbReserva; ?></pNumeroVoucher>
        </GravaReserva>
    </soap:Body>
</soap:Envelope>