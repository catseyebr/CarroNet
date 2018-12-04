<requisicao-transacao id="af32f93c-5e9c-4f44-9478-ccc5aca9319e"  versao="1.2.1">
    <dados-ec>
        <numero>1006993069</numero>
        <chave>25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3</chave>
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
