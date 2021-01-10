<?php
/**
 * Usuario de la aplicación
 *
 * @author daniel
 */
class Usuario {
    
    //--------------------ATRIBUTOS
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
    
    //--------------------FUNCIONES
    /**
     * Devuelve true si el usuario contiene '1' entre sus roles
     */
    public function isAdmin() {
        $resultado = false;
        foreach($this->rol as $valor) {
            if ($valor == 1) {
                $resultado = true;
            }
        }
        return $resultado;
    }
    
    /**
     * Devuelve true si el usuario contiene '0' entre sus roles
     */
    public function isAutor() {
        $resultado = false;
        foreach($this->rol as $valor) {
            if ($valor == 0) {
                $resultado = true;
            }
        }
        return $resultado;
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
