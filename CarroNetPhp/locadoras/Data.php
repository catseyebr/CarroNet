<?php

/**
 * Data short summary.
 *
 * Data description.
 *
 * @version 1.0
 * @author Eduardo Assis
 */
class Data
{
    private $dia; //integer
	private $mes; //integer
	private $ano; //integer
	private $hora; //integer
	private $minuto; //integer
	private $segundo; //integer
	private $semana; //integer
	private $timestamp; //integer
	private $valores; //array
	private $hora_inicial; //string
	private $hora_final; //string
	private $horario_atendimento; //boolean
	private $proximo_atendimento; //timestamp
	private $feriado; //boolean
	function __construct($datahora = NULL, $tipo = NULL) {
		if (!$datahora)
			$datahora = date("Y-m-d\TH:i:s");
		if ($tipo == 1) {
			$this->recebeDataSql($datahora);
		} else {
			$this->recebeDataDefault($datahora);
		}
	}

	private function recebeDataDefault($datahora = NULL) {
		//Formato padrão: Y-m-dTH:m:s
		$dt = explode('T', $datahora);
		$this->setData($dt);
	}

	private function recebeDataSql($datahora = NULL) {
		//Formato sql: Y-m-d H:m:s
		$dt = explode(' ', $datahora);
		$this->setData($dt);
	}

	private function setData($dt = array()) {
		$ano = substr($dt[0], 0, 4);
		$mes = substr($dt[0], 5, 2);
		$dia = substr($dt[0], 8, 2);
		if (isset($dt[1])) {
			$time = explode(':', $dt[1]);
			$hora = $time[0];
			$minuto = (isset($time[1]))?$time[1]:0;
			if (isset($time[2])) {
				$segundo = $time[2];
			} else {
				$segundo = 0;
			}
		} else {
			$hora = 0;
			$minuto = 0;
			$segundo = 0;
		}
		$timestamp = mktime((int)$hora, (int)$minuto, (int)$segundo, (int)$mes, (int)$dia, (int)$ano);
		$this->setTimestamp($timestamp);
	}

	public function getDia() {
		return $this->dia;
	}

	public function getMes() {
		return $this->mes;
	}

	public function getNomeMes() {
        $returnData = "";
		switch ($this->mes) {
			case 1:
				$returnData = "Janeiro";
				break;
			case 2:
				$returnData = "Fevereiro";
				break;
			case 3:
				$returnData = "Março";
				break;
			case 4:
				$returnData = "Abril";
				break;
			case 5:
				$returnData = "Maio";
				break;
			case 6:
				$returnData = "Junho";
				break;
			case 7:
				$returnData = "Julho";
				break;
			case 8:
				$returnData = "Agosto";
				break;
			case 9:
				$returnData = "Setembro";
				break;
			case 10:
				$returnData = "Outubro";
				break;
			case 11:
				$returnData = "Novembro";
				break;
			case 12:
				$returnData = "Dezembro";
				break;
		}
		return $returnData;
	}

	public function getAbrNomeMes() {
        $returnData = "";
		switch ($this->mes) {
			case 1:
				$returnData = "Jan";
				break;
			case 2:
				$returnData = "Fev";
				break;
			case 3:
				$returnData = "Mar";
				break;
			case 4:
				$returnData = "Abr";
				break;
			case 5:
				$returnData = "Mai";
				break;
			case 6:
				$returnData = "Jun";
				break;
			case 7:
				$returnData = "Jul";
				break;
			case 8:
				$returnData = "Ago";
				break;
			case 9:
				$returnData = "Set";
				break;
			case 10:
				$returnData = "Out";
				break;
			case 11:
				$returnData = "Nov";
				break;
			case 12:
				$returnData = "Dez";
				break;
		}
		return $returnData;
	}

	public function getAno() {
		return $this->ano;
	}

	public function getHora() {
		return $this->hora;
	}

	public function getMinuto() {
		return $this->minuto;
	}

	public function getSegundo() {
		return $this->segundo;
	}

	public function getSemana() {
		return $this->semana;
	}

	public function getNomeSemana() {
        $returnData = "";
		switch ($this->semana) {
			case 0:
				$returnData = "Domingo";
				break;
			case 1:
				$returnData = "Segunda-feira";
				break;
			case 2:
				$returnData = "Terça-feira";
				break;
			case 3:
				$returnData = "Quarta-feira";
				break;
			case 4:
				$returnData = "Quinta-feira";
				break;
			case 5:
				$returnData = "Sexta-feira";
				break;
			case 6:
				$returnData = "Sábado";
				break;
		}
		return $returnData;
	}

    public function getNomeSemanaCurto() {
        $returnData = "";
        switch ($this->semana) {
            case 0:
                $returnData = "Domingo";
                break;
            case 1:
                $returnData = "Segunda";
                break;
            case 2:
                $returnData = "Terça";
                break;
            case 3:
                $returnData = "Quarta";
                break;
            case 4:
                $returnData = "Quinta";
                break;
            case 5:
                $returnData = "Sexta";
                break;
            case 6:
                $returnData = "Sábado";
                break;
        }
        return $returnData;
    }

	public function getAbrSemana() {
        $returnData = "";
		switch ($this->semana) {
			case 0:
				$returnData = "Dom";
				break;
			case 1:
				$returnData = "Seg";
				break;
			case 2:
				$returnData = "Ter";
				break;
			case 3:
				$returnData = "Qua";
				break;
			case 4:
				$returnData = "Qui";
				break;
			case 5:
				$returnData = "Sex";
				break;
			case 6:
				$returnData = "Sab";
				break;
		}
		return $returnData;
	}

	public function getTimestamp() {
		return $this->timestamp;
	}

	public function getDataHoraBr() {
		$datahorabr = str_pad($this->dia, 2, '0', STR_PAD_LEFT) . "/" . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . "/" . $this->ano . " " . str_pad($this->hora, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->minuto, 2, '0', STR_PAD_LEFT);
		return $datahorabr;
	}

	public function getDataWeekHoraBr() {
		$datahorabr = str_pad($this->dia, 2, '0', STR_PAD_LEFT) . "/" . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . "/" . $this->ano . " (" . $this->getAbrSemana() . ") " . str_pad($this->hora, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->minuto, 2, '0', STR_PAD_LEFT);
		return $datahorabr;
	}

    public function getDataWeekHourBr() {
        $datahorabr = str_pad($this->dia, 2, '0', STR_PAD_LEFT) . "/" . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . "/" . substr($this->ano,2,2) . " - " . $this->getAbrSemana() . " | " . str_pad($this->hora, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->minuto, 2, '0', STR_PAD_LEFT);
        return $datahorabr;
    }

    public function getDataHoraWeekBr() {
        $datahorabr = $this->getAbrSemana() .", ".str_pad($this->dia, 2, '0', STR_PAD_LEFT) . "/" . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . "/" . $this->ano . " - " . str_pad($this->hora, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->minuto, 2, '0', STR_PAD_LEFT);
        return $datahorabr;
    }

    public function getDataHoraFullWeekBr() {
        $datahorabr = $this->getNomeSemanaCurto() .", ".str_pad($this->dia, 2, '0', STR_PAD_LEFT) . "/" . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . "/" . $this->ano . " | " . str_pad($this->hora, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->minuto, 2, '0', STR_PAD_LEFT);
        return $datahorabr;
    }

	public function getDataHoraExtenso() {
		$datahorabr = $this->getNomeSemana() . ", " . $this->dia . " de " . $this->getNomeMes() . " de " . $this->ano . " às " . str_pad($this->hora, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->minuto, 2, '0', STR_PAD_LEFT) . "h";
		return $datahorabr;
	}

	public function getDataExtenso() {
		$datahorabr = $this->dia . " de " . $this->getNomeMes() . " de " . $this->ano;
		return $datahorabr;
	}

	public function getDataBr() {
		$databr = str_pad($this->dia, 2, '0', STR_PAD_LEFT) . "/" . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . "/" . $this->ano;
		return $databr;
	}

	public function getDataHoraSql() {
		$datahorasql = $this->ano . "-" . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($this->dia, 2, '0', STR_PAD_LEFT) . " " . str_pad($this->hora, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->minuto, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->segundo, 2, '0', STR_PAD_LEFT);
		return $datahorasql;
	}

	public function getDataSql() {
		$datahorasql = $this->ano . "-" . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($this->dia, 2, '0', STR_PAD_LEFT);
		return $datahorasql;
	}

	public function getDataHora() {
		$datahorasql = $this->ano . "-" . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($this->dia, 2, '0', STR_PAD_LEFT) . "T" . str_pad($this->hora, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->minuto, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->segundo, 2, '0', STR_PAD_LEFT);
		return $datahorasql;
	}

	public function getHoraSql() {
		$horasql = str_pad($this->hora, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->minuto, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->segundo, 2, '0', STR_PAD_LEFT);
		return $horasql;
	}

	public function getHoraNoSec() {
		$horasql = str_pad($this->hora, 2, '0', STR_PAD_LEFT) . ":" . str_pad($this->minuto, 2, '0', STR_PAD_LEFT);
		return $horasql;
	}

	public function getValores() {
		return $this->valores;
	}

	public function getHoraInicial() {
		if (!$this->hora_inicial) {
			$this->setHoraInicial();
		}
		return $this->hora_inicial;
	}

	public function getHoraFinal() {
		if (!$this->hora_final) {
			$this->setHoraFinal();
		}
		return $this->hora_final;
	}

	public function getHorarioAtendimento($opt = NULL) {
		$this->setHorarioAtendimento($opt);
		return $this->horario_atendimento;
	}

	public function getProximoAtendimento() {
		$this->setProximoAtendimento();
		return $this->proximo_atendimento;
	}

	public function setDia($dia = NULL) {
		$this->dia = (integer)$dia;
	}

	public function setMes($mes = NULL) {
		$this->mes = (integer)$mes;
	}

	public function setAno($ano = NULL) {
		$this->ano = (integer)$ano;
	}

	public function setHora($hora = NULL) {
		$this->hora = (integer)$hora;
	}

	public function setMinuto($minuto = NULL) {
		$this->minuto = (integer)$minuto;
	}

	public function setSegundo($segundo = NULL) {
		$this->segundo = (integer)$segundo;
	}

	public function setSemana($semana = NULL) {
		$this->semana = (integer)$semana;
	}

	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
		$this->setDia(date("d", $this->timestamp));
		$this->setMes(date("m", $this->timestamp));
		$this->setAno(date("Y", $this->timestamp));
		$this->setSemana(date("w", $this->timestamp));
		$this->setHora(date("H", $this->timestamp));
		$this->setMinuto(date("i", $this->timestamp));
		$this->setSegundo(date("s", $this->timestamp));
	}

	public function setValores($valores) {
		$this->valores = $valores;
	}

	public function setHoraInicial($hora_inicial = NULL) {
		if (!$hora_inicial) {
			$this->hora_inicial = "08:00:00";
		} else {
			$this->hora_inicial = $hora_inicial;
		}
	}

	public function setHoraFinal($hora_final = NULL) {
		if (!$hora_final) {
			$this->hora_final = "20:00:00";
		} else {
			$this->hora_final = $hora_final;
		}
	}

	public function setHorarioAtendimento($opt = NULL) {
		$time_inicial = explode(':', $this->getHoraInicial());
		$hora_inicial = $time_inicial[0];
		$minuto_inicial = $time_inicial[1];
		if (isset($time_inicial[2])) {
			$segundo_inicial = $time_inicial[2];
		} else {
			$segundo_inicial = 0;
		}
		$timestamp_inicial = mktime($hora_inicial, $minuto_inicial, $segundo_inicial, $this->getMes(), $this->getDia(), $this->getAno());
		$semana_atendimento = date("w", $timestamp_inicial);
		$time_final = explode(':', $this->getHoraFinal());
		$hora_final = $time_final[0];
		$minuto_final = $time_final[1];
		if (isset($time_final[2])) {
			$segundo_final = $time_final[2];
		} else {
			$segundo_final = 0;
		}
		$timestamp_final = mktime($hora_final, $minuto_final, $segundo_final, $this->getMes(), $this->getDia(), $this->getAno());
		if ($this->getTimestamp() >= $timestamp_inicial && $this->getTimestamp() <= $timestamp_final && $semana_atendimento != 0 && $semana_atendimento != 6) {
			$this->horario_atendimento = TRUE;
            $this->feriado = FALSE;
		} else {
			$this->horario_atendimento = FALSE;
			$this->feriado = FALSE;
		}
	}

	public function setProximoAtendimento() {
		$time_inicial = explode(':', $this->getHoraInicial());
		$hora_inicial = $time_inicial[0];
		$minuto_inicial = $time_inicial[1];
		if (isset($time_inicial[2])) {
			$segundo_inicial = $time_inicial[2];
		} else {
			$segundo_inicial = 0;
		}
		$timestamp_inicial = mktime($hora_inicial, $minuto_inicial, $segundo_inicial, $this->getMes(), $this->getDia(), $this->getAno());
		$semana_atendimento = date("w", $timestamp_inicial);
		if ($semana_atendimento == 5) {
			$this->proximo_atendimento = mktime($hora_inicial, $minuto_inicial, $segundo_inicial, $this->getMes(), $this->getDia() + 3, $this->getAno());
		} else if ($semana_atendimento == 6) {
			$this->proximo_atendimento = mktime($hora_inicial, $minuto_inicial, $segundo_inicial, $this->getMes(), $this->getDia() + 2, $this->getAno());
		} else {
			$this->proximo_atendimento = mktime($hora_inicial, $minuto_inicial, $segundo_inicial, $this->getMes(), $this->getDia() + 1, $this->getAno());
		}
	}

	public function addDia($dias) {
		$timestamp = mktime($this->hora, $this->minuto, $this->segundo, $this->mes, $this->dia + $dias, $this->ano);
		$this->setTimestamp($timestamp);
	}

	public function subDia($dias) {
		$timestamp = mktime($this->hora, $this->minuto, $this->segundo, $this->mes, $this->dia - $dias, $this->ano);
		$this->setTimestamp($timestamp);
	}

	public function addMes($meses) {
		$timestamp = mktime($this->hora, $this->minuto, $this->segundo, $this->mes + $meses, $this->dia, $this->ano);
		$this->setTimestamp($timestamp);
	}

	public function addAno($anos) {
		$timestamp = mktime($this->hora, $this->minuto, $this->segundo, $this->mes, $this->dia, $this->ano + $anos);
		$this->setTimestamp($timestamp);
	}

	public function addHora($horas) {
		$timestamp = mktime($this->hora + $horas, $this->minuto, $this->segundo, $this->mes, $this->dia, $this->ano);
		$this->setTimestamp($timestamp);
	}

	public function subHora($horas) {
		$timestamp = mktime($this->hora - $horas, $this->minuto, $this->segundo, $this->mes, $this->dia, $this->ano);
		$this->setTimestamp($timestamp);
	}

	public function addMinuto($minutos) {
		$timestamp = mktime($this->hora, $this->minuto + $minutos, $this->segundo, $this->mes, $this->dia, $this->ano);
		$this->setTimestamp($timestamp);
	}

	public function addSegundo($segundos) {
		$timestamp = mktime($this->hora, $this->minuto, $this->segundo + $segundos, $this->mes, $this->dia, $this->ano);
		$this->setTimestamp($timestamp);
	}

	public function addSemana($semanas) {
		$dias = $semanas * 7;
		$timestamp = mktime($this->hora, $this->minuto, $this->segundo, $this->mes, $this->dia + $dias, $this->ano);
		$this->setTimestamp($timestamp);
	}

	public function isFeriado($data, $opt = NULL) {
        $feriado = FALSE;
		return $feriado;
	}
}