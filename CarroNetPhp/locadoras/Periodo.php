<?php
include_once 'Data.php';
/**
 * Periodo short summary.
 *
 * Periodo description.
 *
 * @version 1.0
 * @author Eduardo Assis
 */
class Periodo
{
    /**
     * @var
     */
	private $datahora_inicial; //Data
	/**
     * @var
     */
	private $datahora_final; //Data
	/**
     * @var
     */
	private $datahora_agora; //Data
	/**
     * @var
     */
	private $dias; //integer
	/**
     * @var
     */
	private $dias_calculo; //integer
	/**
     * @var
     */
	private $horas; //integer
	/**
     * @var
     */
	private $diario; //array Data
	/**
     * @var
     */
	private $is_valid; //boolean
	/**
     * @var
     */
	private $is_valid_tole; //boolean
	/**
     * @param null $datahora_inicial
     * @param null $datahora_final
     */
	function __construct($datahora_inicial = NULL, $datahora_final = NULL){
		$this->setDataHoraInicial($datahora_inicial);
		$this->setDataHoraFinal($datahora_final);
		$this->setDataHoraAgora();
		$this->setDias();
		$this->setDiario();
		$this->isValid();
	}

/**
     * @return Data
     */
	public function getDataHoraInicial (){
		return $this->datahora_inicial;
	}

	/**
     * @return Data
     */
	public function getDataHoraFinal (){
		return $this->datahora_final;
	}

	/**
     * @return Data
     */
	public function getDataHoraAgora (){
		return $this->datahora_agora;
	}

	/**
     * @return mixed
     */
	public function getDias (){
		return $this->dias;
	}

	/**
     * @return mixed
     */
	public function getDiasCalculo (){
		if($this->dias_calculo <= 0){
			return $this->dias;
		}else{
			return $this->dias_calculo;
		}
	}

	/**
     * @return mixed
     */
	public function getHoras (){
		return $this->horas;
	}

	/**
     * @return mixed
     */
	public function getDiario (){
		return $this->diario;
	}

	/**
     * @return mixed
     */
	public function getIsValid (){
		return $this->is_valid;
	}

	/**
     * @return bool
     */
	public function getIsValidTole (){
        $data_tolerancia = new Data();
        $data_tolerancia->addMinuto(29);
        if ($this->datahora_inicial->getTimestamp() <= $data_tolerancia->getTimestamp()){
            $this->is_valid_tole = FALSE;
        }else if ($this->datahora_final->getTimestamp() <= $data_tolerancia->getTimestamp()){
            $this->is_valid_tole = FALSE;
        }else{
            $this->is_valid_tole = TRUE;
        }
        return $this->is_valid_tole;
    }

	/**
     * @return string
     */
	public function getDataHoraExtenso (){
		return "de ".$this->getDataHoraInicial()->getDataHoraBr()." a ".$this->getDataHoraFinal()->getDataHoraBr();
	}

	/**
     * @return float
     */
	public function getHorasTotal(){
		return round(($this->getDataHoraFinal()->getTimestamp() - $this->getDataHoraInicial()->getTimestamp())/3600);
	}

	/**
     * @return float
     */
	public function getHorasAgora(){
		return round(($this->getDataHoraInicial()->getTimestamp() - $this->getDataHoraAgora()->getTimestamp())/3600);
	}

    /**
     * @param null $datahora_inicial
     */
	public function setDataHoraInicial ($datahora_inicial = NULL){
		$this->datahora_inicial = new Data($datahora_inicial);
	}

	/**
     * @param null $datahora_final
     */
	public function setDataHoraFinal ($datahora_final = NULL){
		$this->datahora_final = new Data($datahora_final);
	}

	/**
     *
     */
	public function setDataHoraAgora (){
		$this->datahora_agora = new Data();
	}

	/**
     * @param null $dias
     */
	public function setDias ($dias = NULL){
		$horas = ($this->datahora_final->getTimestamp() - $this->datahora_inicial->getTimestamp())/3600;
		$minutos1 = ($this->datahora_inicial->getHora() * 60) + ($this->datahora_inicial->getMinuto());
		$minutos2 = ($this->datahora_final->getHora() * 60) + ($this->datahora_final->getMinuto());
		$minutos = $minutos2 - $minutos1;

		if($horas < 24){
			$this->dias = 1;
			$this->setHoras(0);
		}else{
			$minutos_extra = ($minutos%1440)%60;
			$this->setHoras(($minutos%1440)/60);
			$this->dias = round((integer)($horas-($this->horas))/24);

			if($this->horas < 1 && $this->horas > 0){
				$this->setHoras(1);
			}else{
				$this->setHoras((integer)(($minutos-$minutos_extra)%1440)/60);
				if($minutos_extra >= 1){
					$this->horas += 1;
				}
			}
		}
	}

    /**
     * @param null $dias_calculo
     */
    public function setDiasCalculo ($dias_calculo = NULL){
        $this->dias_calculo = (int)$dias_calculo;
    }

	/**
     * @param $horas
     */
	public function setHoras ($horas){
		$this->horas = $horas;
	}

	/**
     *
     */
	public function setDiario (){
		for($i = 0; $i < $this->dias; $i++){
			$diario = new Data($this->datahora_inicial->getDataHoraSql(),1);
			$diario->addDia($i);
			$this->diario[] = $diario;
		}
	}

	/**
     *
     */
	public function isValid(){
		if ($this->datahora_inicial->getTimestamp() <= $this->getDataHoraAgora()->getTimestamp()){
			$this->is_valid = FALSE;
		}else if ($this->datahora_final->getTimestamp() <= $this->datahora_inicial->getTimestamp()){
			$this->is_valid = FALSE;
		}else{
			$this->is_valid = TRUE;
		}
	}
}