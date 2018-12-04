<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <GetInformacoesTarifa xmlns="http://tempuri.org/">
            <pUsuario>maggi</pUsuario>
            <pSenha>xmlmaggi2017CA</pSenha>
            <pDataRetirada><?php echo $this->dataRetirada; ?></pDataRetirada>
            <pDataRetorno><?php echo $this->dataDevolucao; ?></pDataRetorno>
            <pIdLocalRetirada><?php echo $this->cidadeRetirada; ?></pIdLocalRetirada>
            <pIdLocalRetorno><?php echo $this->cidadeDevolucao; ?></pIdLocalRetorno>
            <pGrupoVeiculo><?php echo $this->sipp; ?></pGrupoVeiculo>
            <pIdOpcionais></pIdOpcionais>
            <pIdentificacao>CAR2017ALU0611</pIdentificacao>
            <pUsuarioReserva>web@layum.com</pUsuarioReserva>
            <pSenhaReserva>Layum0602</pSenhaReserva>
        </GetInformacoesTarifa>
    </soap:Body>
</soap:Envelope>