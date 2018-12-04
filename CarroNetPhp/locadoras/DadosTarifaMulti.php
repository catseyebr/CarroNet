<?php

/**
 * DadosTarifaMulti short summary.
 *
 * DadosTarifaMulti description.
 *
 * @version 1.0
 * @author Eduardo Assis
 */
class DadosTarifaMulti
{
    /**
     * @var array
     */
    private $received_data;
    /**
     * @var array
     */
    private $decoded_data;
    /**
     * @var array
     */
    private $best_data;

    public function __construct($dados)
    {
        if(isset($dados) && is_array($dados))
        {
            $this->setReceivedData($dados);
            $this->convertData();
        }
    }

    /**
     * @return array
     */
    public function getReceivedData()
    {
        return $this->received_data;
    }

    /**
     * @param array $received_data
     * @return DadosTarifaMulti
     */
    public function setReceivedData($received_data)
    {
        $this->received_data = $received_data;
        return $this;
    }

    /**
     * @return array
     */
    public function getDecodedData()
    {
        return $this->decoded_data;
    }

    /**
     * @param array $decoded_data
     * @return DadosTarifaMulti
     */
    public function setDecodedData($decoded_data)
    {
        $this->decoded_data = $decoded_data;
        return $this;
    }

    /**
     * @return array
     */
    public function getBestData()
    {
        $bData["ReturnDateTime"] = reset($this->getDecodedData())["ReturnDateTime"];
        $bData["PickUpDateTime"] = reset($this->getDecodedData())["PickUpDateTime"];
        $bData["PickUpLocation"] = reset($this->getDecodedData())["PickUpLocation"];
        $bData["ReturnLocation"] = reset($this->getDecodedData())["ReturnLocation"];
        $bData["vendoravail"]= $this->best_data;
        return $bData;
    }

    /**
     * @return DadosTarifaMulti
     */
    public function setBestData()
    {
        if(is_array($this->getDecodedData()))
        {
            $avail = [];
            foreach($this->getDecodedData() as $data){
                foreach($data['vendoravail'] as $keygrp => $group){
                    if(array_key_exists($keygrp,$avail)){
                        if(isset($group['VehicleCharges']['1']['Calculation_Total']) && $avail[$keygrp]['VehicleCharges']['1']['Calculation_Total'] > $group['VehicleCharges']['1']['Calculation_Total']){
                            $avail[$keygrp] = $group;
                        }
                    }else{
                        $avail[$keygrp] = $group;
                    }
                }
            }
        }
        $this->best_data = $avail;
        return $this;
    }

    private function convertData()
    {
        if (isset($this->received_data) && is_array($this->received_data))
        {
            $convData = [];
            foreach($this->received_data as $key => $data)
            {
                $convData[$key] = json_decode($data,1);
            }
            $this->setDecodedData($convData);
            $this->setBestData();
        }
    }
}