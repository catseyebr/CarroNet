<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    include_once 'TarifaOnlineWs.php';
    $opt_online_loc = array(
                'periodo' => $this->periodo,
                'locadora' => $this->getLocadora(),
                'arr_lojas_retirada' => $this->lojas_retirada,
                'arr_lojas_devolucao' => $this->getLojasDevolucao(),
                'promo_codigo' => $this->getCodPromocional()
            );
    $pesquisa = new TarifaOnlineWs($this->getLocadora(),$opt_online_loc);
?>