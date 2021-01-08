<?php
/**
 * Usuario de la aplicación
 *
 * @author daniel
 */
class Usuario {
    
    //--------------------PROPIEDADES
    private $id;
    private $nombre;
    private $correo;
    private $rol;
    private $activo;
    
    //--------------------CONSTRUCTOR
    function __construct($id, $nombre, $correo, $rol, $activo) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->rol = $rol;
        $this->activo = $activo;
    }
    
    //--------------------GET & SET
    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getCorreo() {
        return $this->correo;
    }

    function getRol() {
        return $this->rol;
    }

    function getActivo() {
        return $this->activo;
    }

    //--------------------TO STRING
    public function __toString() {
        return '[USUARIO] ID: ' . $this->id . ' | Nombre: ' . $this->nombre . ' | Correo: ' . $this->correo . ' | Rol: ' . $this->rol[0] . ' | Activo: ' . $this->activo;
    }
    
}
