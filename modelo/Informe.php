<?php

/**
 * Informe semanal y por región de infectados por COVID
 *
 * @author daniel
 */
class Informe {

    //--------------------ATRIBUTOS
    private $id;
    private $semana;
    private $region;
    private $nInfectados;
    private $nFallecidos;
    private $nAltas;
    private $idAutor;

    //--------------------CONSTRUCTOR
    function __construct($id, $semana, $region, $nInfectados, $nFallecidos, $nAltas, $idAutor) {
        $this->id = $id;
        $this->semana = $semana;
        $this->region = $region;
        $this->nInfectados = $nInfectados;
        $this->nFallecidos = $nFallecidos;
        $this->nAltas = $nAltas;
        $this->idAutor = $idAutor;
    }

    //--------------------GET & SET
    function getId() {
        return $this->id;
    }

    function getSemana() {
        return $this->semana;
    }

    function getRegion() {
        return $this->region;
    }

    function getNInfectados() {
        return $this->nInfectados;
    }

    function getNFallecidos() {
        return $this->nFallecidos;
    }

    function getNAltas() {
        return $this->nAltas;
    }

    function getIdAutor() {
        return $this->idAutor;
    }

}
