<?php

class Region {

    //--------------------ATRIBUTOS
    private $id;
    private $nombre;

    //--------------------CONSTRUCTOR
    function __construct($id, $nombre) {
        $this->id = $id;
        $this->nombre = $nombre;
    }
    
    //--------------------GET
    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

}
