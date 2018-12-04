<?php
include_once 'Periodo.php';
include_once 'Locadora.php';
include_once 'DadosTarifaMulti.php';
/**
 * TarifaOnlineWs short summary.
 *
 * TarifaOnlineWs description.
 *
 * @version 1.0
 * @author eduar
 */
class TarifaOnlineWs
{
    protected $conn;
	protected $id;
	/**
	 * @var Periodo
	 */
	protected $periodo;
	/**
	 * @var Locadora
	 */
	protected $locadora;
	protected $loja_retirada;
	protected $loja_devolucao;
	protected $dados_tarifa;
	protected $promo_codigo;
    protected $obj_loja_retirada;
	protected $sipp;
	protected $lojas_reti_arr;
	protected $lojas_devo_arr;
	protected $lojas_reti_obj;
	protected $lojas_devo_obj;
	private $results;

	public function __construct($id, $options = NULL) {
		$this->id = $id;
		if($options) {
			$this->periodo = $options['periodo'];
			$this->locadora = $options['locadora'];
			$this->loja_retirada = $options['loja_retirada'];
			$this->loja_devolucao = $options['loja_devolucao'];
			$this->obj_loja_retirada = $options['obj_loja_retirada'];
			$this->promo_codigo = $options['promo_codigo'];
			$this->setLojasRetiArr($options['arr_lojas_retirada']);
			$this->setLojasDevoArr($options['arr_lojas_devolucao']);
            if($options['sipp']){
                $this->sipp = $options['sipp'];
			}

            $this->consultar();
		}
	}

	public function getLojasRetiArr(){
		return $this->lojas_reti_arr;
	}

	public function getLojasRetiObj(){
		return $this->lojas_reti_obj;
	}

	public function setLojasRetiArr($ljs_obj = NULL){
		if($ljs_obj){
			foreach($ljs_obj as $sipp_reti){
				$this->lojas_reti_arr[$sipp_reti->getIata()] = $sipp_reti->getIata();
				$this->lojas_reti_obj[$sipp_reti->getIata()] = $sipp_reti;
			}
		}
	}

	public function getLojasDevoArr(){
		return $this->lojas_devo_arr;
	}

	public function getLojasDevoObj(){
		return $this->lojas_devo_obj;
	}

	public function setLojasDevoArr($ljs_obj = NULL){
		if($ljs_obj){
			foreach($ljs_obj as $sipp_devo){
				$this->lojas_devo_arr[$sipp_devo->getIata()] = $sipp_devo->getIata();
				$this->lojas_devo_obj[$sipp_devo->getIata()] = $sipp_devo;
			}
		}
	}

    public function consultar() {
        if(is_object($this->locadora)) {
            $data_now = new Data();
            $urls = array();

            foreach ($this->getLojasRetiObj() as $lj_reti) {
                $promo_cod = NULL;
                $url = "http://www.carroaluguel.net/locadoras/pesquisa.php?ren=".$this->locadora->getXmlVar()."&renrd=".$this->locadora->getXmlRdcar()."&perini=".$this->periodo->getDataHoraInicial()->getDataHora()."&perfim=".$this->periodo->getDataHoraFinal()->getDataHora()."&dias=".$this->periodo->getDias();
                if($this->promo_codigo){
                    $promo_cod = $this->promo_codigo;
                }
                $url .= '&stpick=' . $lj_reti->getId() . '&stpickiata=' . $lj_reti->getIata() . '&stret=' . $lj_reti->getId().'&stretiata=' . $lj_reti->getIata().'&ratequal=' . $lj_reti->getRateQual($this->periodo->getDataHoraInicial()).'&codpromo='.$promo_cod.'&stamp='.$data_now->getTimeStamp();
                $urls[] = $url;
            }
            $this->rolling_curl($urls);
            //$this->openUrls($urls);

            $tratadados = new DadosTarifaMulti($this->results);
            $this->dados_tarifa = $tratadados->getBestData();
        }
    }

	public function getDadosTarifa(){
		return $this->dados_tarifa;
	}

	public function getLocadora(){
		return $this->locadora;
	}

	public function addResults($result)
    {
        $this->results[] = $result;
    }

    function openUrls($urls)
    {
        foreach($urls as $url) {
            $handle = file_get_contents ($url);
            $this->addResults($handle);
        }
    }

    function rolling_curl($urls, $custom_options = null) {

        // make sure the rolling window isn't greater than the # of urls
        $rolling_window = 5;
        $rolling_window = (sizeof($urls) < $rolling_window) ? sizeof($urls) : $rolling_window;

        $master = curl_multi_init();
        $curl_arr = array();

        // add additional curl options here
        $std_options = array(CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5);
        $options = ($custom_options) ? ($std_options + $custom_options) : $std_options;

        // start the first batch of requests
        for ($i = 0; $i < $rolling_window; $i++) {
                $ch = curl_init();
                $options[CURLOPT_URL] = $urls[$i];
                curl_setopt_array($ch,$options);
                curl_multi_add_handle($master, $ch);
            }

        do {
            while(($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);
            if($execrun != CURLM_OK)
                break;
            // a request was just completed -- find out which one
            while($done = curl_multi_info_read($master)) {
                $info = curl_getinfo($done['handle']);
                if ($info['http_code'] == 200)  {
                    $output = curl_multi_getcontent($done['handle']);

                    // request successful.  process output using the callback function.
                    $this->addResults($output);

                    // start a new request (it's important to do this before removing the old one)
                    $ch = curl_init();
                    $options[CURLOPT_URL] = $urls[$i++];  // increment i
                    curl_setopt_array($ch,$options);
                    curl_multi_add_handle($master, $ch);

                    // remove the curl handle that just completed
                    curl_multi_remove_handle($master, $done['handle']);
                } else {
                    // request failed.  add error handling.
                }
            }
        } while ($running);

        curl_multi_close($master);
        return true;
    }
}