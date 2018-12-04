<?php

/**
 * Locadora short summary.
 *
 * Locadora description.
 *
 * @version 1.0
 * @author Eduardo Assis
 */
class Locadora
{
    /**
     * Summary of $id
     * @var int
     */
    private $id;
    /**
     * Summary of $xmlVar
     * @var string
     */
    private $xmlVar;
    /**
     * Summary of $xmlRdCar
     * @var string
     */
    private $xmlRdCar;
    /**
     * Summary of $nome
     * @var string
     */
    private $nome;

    public function __construct($id)
    {
        $this->id = $id;
        $this->setLocadora;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getXmlVar()
    {
        return $this->xmlVar;
    }

    public function setXmlVar($xmlvar)
    {
        $this->xmlVar = $xmlvar;
        return $this;
    }

    public function getXmlRdCar()
    {
        return $this->xmlRdCar;
    }

    public function setXmlRdCar($xmlrdcar)
    {
        $this->xmlRdCar = $xmlrdcar;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function setLocadora()
    {
        $locadoras = [
            [3] = [
                'id' => 3,
                'xmlVar' => 'unidas',
                'xmlRdCar' => '',
                'nome' => 'Unidas Rent a Car'
            ],
            [5] = [
                'id' => 5,
                'xmlVar' => 'yes',
                'xmlRdCar' => '',
                'nome' => 'Yes Rent a Car'
            ],
            [15] = [
                'id' => 15,
                'xmlVar' => 'movida',
                'xmlRdCar' => '',
                'nome' => 'Movida Rent a Car'
            ],
            [95] = [
                'id' => 95,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '3',
                'nome' => 'Inova Rent a Car'
            ],
            [119] = [
                'id' => 119,
                'xmlVar' => 'budget',
                'xmlRdCar' => '',
                'nome' => 'Budget Rent a Car'
            ],
            [124] = [
                'id' => 124,
                'xmlVar' => 'avis',
                'xmlRdCar' => '',
                'nome' => 'Avis Rent a Car'
            ],
            [262] = [
                'id' => 262,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '2',
                'nome' => 'Referência Rent a Car'
            ],
            [286] = [
                'id' => 286,
                'xmlVar' => 'fleetmax',
                'xmlRdCar' => '226',
                'nome' => 'Rental Line Locadora'
            ],
            [288] = [
                'id' => 288,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '6',
                'nome' => 'Mister Car Rent a Car'
            ],
            [446] = [
                'id' => 446,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '116',
                'nome' => 'Alugue Brasil Rent a Car'
            ],
            [517] = [
                'id' => 517,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '296',
                'nome' => 'BM Rent a Car'
            ],
            [527] = [
                'id' => 527,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '526',
                'nome' => 'Canoense Rent a Car'
            ],
            [594] = [
                'id' => 594,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '490',
                'nome' => 'Karper Rent a Car'
            ],
            [693] = [
                'id' => 693,
                'xmlVar' => 'foco',
                'xmlRdCar' => '',
                'nome' => 'Foco Rent a Car'
            ],
            [768] = [
                'id' => 768,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '534',
                'nome' => 'LTR Rent a Car'
            ],
            [1323] = [
                'id' => 1323,
                'xmlVar' => 'localiza',
                'xmlRdCar' => '',
                'nome' => 'Localiza Rent a Car'
            ],
            [1410] = [
                'id' => 1410,
                'xmlVar' => 'maggi',
                'xmlRdCar' => '',
                'nome' => 'Maggi Rent a Car'
            ],
            [1681] = [
                'id' => 1681,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '514',
                'nome' => 'Maxirent Locadora de Veículos'
            ],
            [1752] = [
                'id' => 1752,
                'xmlVar' => 'alamo',
                'xmlRdCar' => '',
                'nome' => 'Alamo Rent a Car'
            ],
            [1773] = [
                'id' => 1773,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '509',
                'nome' => 'Memphis Rent a Car'
            ],
            [1822] = [
                'id' => 1822,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '517',
                'nome' => 'RHP Rent a Car'
            ],
            [2264] = [
                'id' => 2264,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '504',
                'nome' => 'Alternativacar Locadora'
            ],
            [2319] = [
                'id' => 2319,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '529',
                'nome' => 'Carro Reserva'
            ],
            [2340] = [
                'id' => 2340,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '505',
                'nome' => 'Pole Locadora'
            ],
            [2347] = [
                'id' => 2347,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '531',
                'nome' => 'Opportunity Rent a Car'
            ],
            [2376] = [
                'id' => 2376,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '539',
                'nome' => 'Berlim Aluguel de Carros'
            ],
            [2382] = [
                'id' => 2382,
                'xmlVar' => 'hertzbr',
                'xmlRdCar' => '',
                'nome' => 'Hertz Rent a Car'
            ],
            [2390] = [
                'id' => 2390,
                'xmlVar' => 'nacional',
                'xmlRdCar' => '',
                'nome' => 'National Rent a Car'
            ],
            [2397] = [
                'id' => 2397,
                'xmlVar' => 'rdcars',
                'xmlRdCar' => '542',
                'nome' => 'Alles Rent a Car'
            ]
        ];
        $this->setXmlVar($locadoras[$this->id]['xmlVar']);
        $this->setXmlRdCar($locadoras[$this->id]['xmlRdCar']);
        $this->setNome($locadoras[$this->id]['nome']);
    }
}