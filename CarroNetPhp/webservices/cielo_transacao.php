<requisicao-transacao id="af32f93c-5e9c-4f44-9478-ccc5aca9319e"  versao="1.2.1">
    <dados-ec>
        <numero>1028961291</numero>
        <chave>c525a5d5397b6cf3ab4aa171089bc351634b8d42b15f77bf37e3782fee5a957e</chave>
    </dados-ec>
    <dados-portador>
        <numero><?php echo $this->cardNumber; ?></numero>
        <validade><?php echo $this->cardExpDate; ?></validade>
        <indicador>1</indicador>
        <codigo-seguranca><?php echo $this->cardCvc; ?></codigo-seguranca>
    </dados-portador>
    <dados-pedido>
        <numero><?php echo $this->cardPedido; ?></numero>
        <valor><?php echo $this->cardValor; ?></valor>
        <moeda>986</moeda>
        <data-hora><?php echo date('Y-m-d\TH:i:s') ?></data-hora>
        <idioma>PT</idioma>
    </dados-pedido>
    <forma-pagamento>
        <bandeira><?php echo $this->cardBandeira; ?></bandeira>
        <produto>1</produto>
        <parcelas>1</parcelas>
    </forma-pagamento>
    <url-retorno>null</url-retorno>
    <autorizar>3</autorizar>
    <capturar>true</capturar>
</requisicao-transacao>
