<?php

/**
 * ConsultaWs short summary.
 *
 * ConsultaWs description.
 *
 * @version 1.0
 * @author eduar
 */
ini_set("allow_url_fopen", 1);
ini_set("allow_url_include", 1);
class ConsultaWs
{
    /**
     * @var array
     */
    public $postData;
    /**
     * @var string
     */
    public $locadora;
    /**
     * @var string
     */
    public $locadora_rdcars;
    /**
     * @var string
     */
    public $periodo_ini;
    /**
     * @var string
     */
    public $periodo_fim;
    /**
     * @var int
     */
    public $dias;
    /**
     * @var int
     */
    public $loja_reti;
    /**
     * @var string
     */
    public $loja_reti_iata;
    /**
     * @var int
     */
    public $loja_devo;
    /**
     * @var string
     */
    public $loja_devo_iata;
    /**
     * @var string
     */
    private $rate_qual;
    /**
     * @var string
     */
    public $cod_promocional;
    /**
     * @var array
     */
    public $dados_tarifa;

    function __construct($postdata)
    {
        define("WS_PATH", '../webservices/');
        require_once WS_PATH . 'webservice_handler/factory.php';
        $this->postData = $postdata;
        $this->translatePost($this->postData);
    }

    function index()
    {
        $this->consultar();
        echo json_encode($this->dados_tarifa);
    }

    private function translatePost($postData = null)
    {
        if ($postData) {
            if (isset($postData['ren'])) {
                $this->setLocadora($postData['ren']);
            }
            if (isset($postData['renrd'])) {
                $this->setLocadoraRdCars($postData['renrd']);
            }
            if (isset($postData['perini'])) {
                $this->setPeriodoIni($postData['perini']);
            }
            if (isset($postData['perfim'])) {
                $this->setPeriodoFim($postData['perfim']);
            }
            if (isset($postData['dias'])) {
                $this->setDias($postData['dias']);
            }
            if (isset($postData['stpick'])) {
                $this->setLojaRetirada($postData['stpick']);
            }
            if (isset($postData['stpickiata'])) {
                $this->setLojaRetiradaIata($postData['stpickiata']);
            }
            if (isset($postData['stret'])) {
                $this->setLojaDevolucao($postData['stret']);
            }
            if (isset($postData['stretiata'])) {
                $this->setLojaDevolucaoIata($postData['stretiata']);
            }
            if (isset($postData['ratequal'])) {
                $this->setRateQual($postData['ratequal']);
            }
            if (isset($postData['codpromo'])) {
                $this->setCodPromocional($postData['codpromo']);
            }
        }
    }

    private function setLocadora($rentalCode = null)
    {
        if ($rentalCode) {
            $this->locadora = $rentalCode;
        }
    }

    private function setLocadoraRdCars($rentalRd = null)
    {
        if ($rentalRd) {
            $this->locadora_rdcars = $rentalRd;
        }
    }

    private function setPeriodoIni($period_ini = null)
    {
        if ($period_ini) {
            $this->periodo_ini = $period_ini;
        }
    }

    private function setPeriodoFim($period_fim = null)
    {
        if ($period_fim) {
            $this->periodo_fim = $period_fim;
        }
    }

    private function setDias($dias = null)
    {
        if ($dias) {
            $this->dias = (int)$dias;
        }
    }

    private function setLojaRetirada($lj_reti = NULL)
    {
        if ($lj_reti) {
            $this->loja_reti = $lj_reti;
        }
    }

    private function setLojaRetiradaIata($lj_reti_iata = NULL)
    {
        if ($lj_reti_iata) {
            $this->loja_reti_iata = $lj_reti_iata;
        }
    }

    private function setLojaDevolucao($lj_devo = NULL)
    {
        if ($lj_devo) {
            $this->loja_devo = $lj_devo;
        }
    }

    private function setLojaDevolucaoIata($lj_devo_iata = NULL)
    {
        if ($lj_devo_iata) {
            $this->loja_devo_iata = $lj_devo_iata;
        }
    }

    private function setRateQual($rate_qual = NULL)
    {
        if($rate_qual){
            $this->rate_qual = $rate_qual;
        }
    }

    /**
     * @param mixed $cod_promocional
     * @return ConsultaWS
     */
    public function setCodPromocional($cod_promocional)
    {
        $this->cod_promocional = $cod_promocional;
        return $this;
    }

    public function consultar() {

        if(isset($this->locadora)) {

            $disponibilidade = WebService_Handler_Factory::create($this->locadora);
            $dados = array(
                'dataRetirada'    		=> $this->periodo_ini,
                'dataDevolucao'   		=> $this->periodo_fim,
                'locadora_short'  		=> $this->locadora,
                'cidadeRetirada'        => $this->loja_reti_iata,
                'cidadeDevolucao'       => $this->loja_devo_iata,
                'reti_id'               => $this->loja_reti,
                'devo_id'               => $this->loja_devo,
                'ratequal'              => $this->rate_qual,
                'arCondicionado'  		=> NULL,
                'tipo'            		=> NULL,
                'categoria'       		=> NULL,
                'transmissao'	  		=> NULL,
                'door'	  		  		=> NULL,
                'sipp'			  		=> NULL,
                'nome_locadora'			=> $this->locadora,
                'protecao'		  		=> NULL,
                'nmb_diarias'	  	  	=> $this->dias,
                'preferences'			=> 'preferred',
                "promocod"				=> $this->cod_promocional,
                "loc_rdcars"            => $this->locadora_rdcars
            );

            $pesquisa_online = $disponibilidade->setData($dados)->pesquisar();
            
            if($pesquisa_online->is_ok == 1){
                $dados_tarifa = $pesquisa_online->arr_dados;
                $this->dados_tarifa =  $dados_tarifa;
            }else{
                $this->dados_tarifa = 'Nenhum resultado encontrado';
            }
        }
    }
}