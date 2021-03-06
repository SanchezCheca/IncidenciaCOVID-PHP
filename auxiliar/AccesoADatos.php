<?php

/**
 * Clase que se encarga de la gestión de la BD
 * Utiliza la CLASE mysqli
 *
 * @author daniel
 */
//COMPRUEBA EL DIRECTORIO ACTUAL PARA ESTABLECER UNA RUTA ADECUADA A LOS REQUIRE
$dir = $_SERVER['PHP_SELF'];

if (substr($dir, -9) == 'index.php') {
    //Estamos en inicio
    $ruta = '';
} else {
    //NO estamos en inicio
    $ruta = '../';
}

require_once $ruta . 'modelo/Usuario.php';
require_once $ruta . 'modelo/Informe.php';
require_once $ruta . 'modelo/Region.php';
require_once $ruta . 'auxiliar/Variables.php';

class AccesoADatos {

    private static $conexion;

    /**
     * Crea una nueva conexión a BD
     */
    public static function new() {
        // Utilizando la forma procedimental.
        self::$conexion = new mysqli(Variables::$HOST, Variables::$USUARIO, Variables::$PASS, Variables::$BD);

        if (self::$conexion->connect_errno) {
            print "Fallo al conectar a MySQL: " . mysqli_connect_error();
        }
    }

    /**
     * Cierra la conexión a BD
     */
    public static function closeDB() {
        self::$conexion->close();
    }

    /**
     * Devuelve true si existe el usuario cuyo correo recibe por parámetro
     * @param type $correo
     * @return boolean
     */
    public static function isUser($correo) {
        $existe = false;

        self::new();
        $consulta = "SELECT * FROM usuarios WHERE correo='" . $correo . "';";

        $resultado = self::$conexion->query($consulta);
        if ($fila = $resultado->fetch_assoc()) {
            $existe = true;
        }
        self::closeDB();

        return $existe;
    }

    /**
     * Inserta un usuario en la BD encriptando la contraseña. Devuelve el éxito de la operación
     * @param type $correo
     * @param type $nombre
     * @param type $pass
     */
    public static function insertUser($correo, $nombre, $pass) {
        $resultado = false;
        //ENCRIPTA LA CONTRASEÑA
        $passEncriptada = crypt($pass);

        self::new();
        $query = 'INSERT INTO usuarios VALUES(id, "' . $nombre . '", "' . $correo . '", "' . $passEncriptada . '", 0)';
        if (!self::$conexion->query($query)) {
            $resultado = 'Error al insertar: ' . mysqli_error(self::$conexion);
            self::closeDB();
        } else {
            self::closeDB();
            //Le asigna el rol 0 (gestor de datos)
            self::insertRolByCorreo($correo, 0);
            $resultado = true;
        }

        return $resultado;
    }

    /**
     * Asigna un rol a un usuario mediante su correo en la tabla 'usuario_rol'
     * @param type $correo
     * @param type $rol
     */
    public static function insertRolByCorreo($correo, $rol) {
        //Recupera el id del usuario asignado a ese correo
        $idUsuario = self::getIdByCorreo($correo);

        self::new();
        $query = 'INSERT INTO usuario_rol VALUES(' . $idUsuario . ', ' . $rol . ')';
        self::$conexion->query($query);
        self::closeDB();
    }

    /**
     * Asigna un rol a un usuario mediante su id en la tabla 'usuario_rol'
     * @param type $correo
     * @param type $rol
     */
    public static function insertRolById($id, $rol) {
        self::new();
        $query = 'INSERT INTO usuario_rol VALUES(' . $id . ', ' . $rol . ')';
        self::$conexion->query($query);
        self::closeDB();
    }

    /**
     * Devuelve el ID asociado a una cuenta segun su correo
     * @param type $correo
     * @return type
     */
    public static function getIdByCorreo($correo) {
        $id = null;

        self::new();
        $query = 'SELECT id FROM usuarios WHERE correo=?';
        $stmt = self::$conexion->prepare($query);
        $stmt->bind_param("s", $valor1);
        $valor1 = $correo;
        $stmt->execute();
        $result = $stmt->get_result();

        if ($fila = $result->fetch_assoc()) {
            $id = $fila['id'];
        }

        return $id;
    }

    /**
     * Devuelve el/los rol/es del usuario segun su correo
     * @param type $correo
     */
    public static function getRolByCorreo($correo) {
        $rol = [];
        $idUsuario = self::getIdByCorreo($correo);

        self::new();
        $query = 'SELECT idRol FROM usuario_rol WHERE idUsuario =' . $idUsuario;
        if ($resultado = self::$conexion->query($query)) {
            while ($fila = $resultado->fetch_assoc()) {
                $idRol = $fila['idRol'];
                $rol[] = $idRol;
            }

            $resultado->free();
        }
        self::closeDB();
        return $rol;
    }

    /**
     * Devuelve el/los rol/es del usuario segun su id
     * @param type $correo
     */
    public static function getRolById($id) {
        $rol = [];

        self::new();
        $query = 'SELECT idRol FROM usuario_rol WHERE idUsuario =' . $id;
        if ($resultado = self::$conexion->query($query)) {
            while ($fila = $resultado->fetch_assoc()) {
                $idRol = $fila['idRol'];
                $rol[] = $idRol;
            }

            $resultado->free();
        }
        self::closeDB();
        return $rol;
    }

    /**
     * Elimina un rol de un usuario de la tabla usuario_rol
     * @param type $idUsuario
     * @param type $idRol
     */
    public static function removeRol($idUsuario, $idRol) {
        self::new();
        $query = 'DELETE FROM usuario_rol WHERE idUsuario=' . $idUsuario . ' AND idRol=' . $idRol;
        self::$conexion->query($query);
        self::closeDB();
    }

    /**
     * Devuelve el correo electrónico de un usuario según su id
     * @param type $id
     */
    public static function getCorreoById($id) {
        self::new();
        $query = 'SELECT correo FROM usuarios WHERE id=' . $id;
        if ($resultado = self::$conexion->query($query)) {
            $fila = $resultado->fetch_assoc();
            $correo = $fila['correo'];
            $resultado->free();
        }
        self::closeDB();
        return $correo;
    }

    /**
     * Devuelve un objeto Usuario si existe y la combinación correo-pass es correcta
     * Si no, devuelve null
     * @param type $correo
     * @param type $pass
     * @return \Usuario
     */
    public static function getUsuario($correo, $pass) {
        $usuario = null;

        self::new();
        $consulta = "SELECT * FROM usuarios WHERE correo=?";
        $stmt = self::$conexion->prepare($consulta);
        $stmt->bind_param("s", $val1);
        $val1 = $correo;
        $stmt->execute();
        $result = $stmt->get_result();

        if ($fila = $result->fetch_assoc()) {
            $id = $fila['id'];
            $nombre = $fila['nombre'];
            $correo = $fila['correo'];
            $activo = $fila['activo'];
            $passEncriptada = $fila['pass'];

            //COMPRUEBA QUE LA CONTRASEÑA SEA CORRECTA
            if (hash_equals($passEncriptada, crypt($pass, $passEncriptada))) {
                //Contraseña correcta, recupera el rol del usuario
                self::closeDB();
                $rol = self::getRolByCorreo($correo);
                $usuario = new Usuario($id, $nombre, $correo, $rol, $activo);
            } else {
                self::closeDB();
            }
            $result->free();
            return $usuario;
        }

        return $usuario;
    }

    /**
     * Devuelve un vector asociativo con todos los usuarios registrados
     */
    public static function getAllUsers() {
        $consulta = "SELECT * FROM usuarios";
        $usuarios = null;

        self::new();
        if ($resultado = self::$conexion->query($consulta)) {
            self::closeDB();
            while ($fila = $resultado->fetch_assoc()) {
                $id = $fila['id'];
                $nombre = $fila['nombre'];
                $correo = $fila['correo'];
                $activo = $fila['activo'];

                $rol = self::getRolByCorreo($correo);

                $usuarios[] = new Usuario($id, $nombre, $correo, $rol, $activo);
            }

            $resultado->free();
        }

        return $usuarios;
    }

    /**
     * Actualiza el nombre, correo y rol de un usuario cuyo id recibe
     * @param type $id
     * @param type $nombre
     * @param type $correo
     * @param type $rol
     */
    public static function updateUser($id, $nombre, $correo, $activo) {
        $sentencia = "UPDATE usuarios SET correo='" . $correo . "', nombre='" . $nombre . "', activo=" . $activo . " WHERE id='" . $id . "';";

        self::new();
        self::$conexion->query($sentencia);
        self::closeDB();
    }

    /**
     * Devuelve true si existe algún informe para esa semana y región
     * @param type $semana
     * @param type $region
     * @return boolean
     */
    public static function isInforme($semana, $region) {
        $existe = false;

        self::new();
        $consulta = 'SELECT * FROM informes WHERE semana="' . $semana . '" AND region="' . $region . '"';

        $resultado = self::$conexion->query($consulta);
        if ($fila = $resultado->fetch_assoc()) {
            $existe = true;
        }
        self::closeDB();

        return $existe;
    }

    /**
     * Introduce un nuevo informe en la BD
     * @param type $semana
     * @param type $region
     * @param type $nInfectados
     * @param type $nFallecidos
     * @param type $nAltas
     * @param type $idAutor
     */
    public static function insertInforme($semana, $region, $nInfectados, $nFallecidos, $nAltas, $idAutor) {
        self::new();
        $query = 'INSERT INTO informes VALUES(id,"' . $semana . '","' . $region . '",' . $nInfectados . ',' . $nFallecidos . ',' . $nAltas . ',' . $idAutor . ')';
        self::$conexion->query($query);
        self::closeDB();
    }

    /**
     * Devuelve un informe segun su id
     * @param type $id
     */
    public static function getInforme($id) {
        $informe = null;
        self::new();
        $query = 'SELECT * FROM informes WHERE id=' . $id;
        if ($resultado = self::$conexion->query($query)) {
            self::closeDB();
            $fila = $resultado->fetch_assoc();

            $idInforme = $fila['id'];
            $semana = $fila['semana'];
            $nInfectados = $fila['nInfectados'];
            $nFallecidos = $fila['nFallecidos'];
            $nAltas = $fila['nAltas'];
            $idAutor = $fila['idautor'];

            //Recupera el NOMBRE de la region
            self::new();
            $region = $fila['region'];
            $consultaRegion = 'SELECT nombre FROM regiones WHERE id=' . $region;
            $resultado = self::$conexion->query($consultaRegion);
            $fila = $resultado->fetch_assoc();
            $region = $fila['nombre'];

            $informe = new Informe($idInforme, $semana, $region, $nInfectados, $nFallecidos, $nAltas, $idAutor);

            $resultado->free();
        }
        self::closeDB();
        return $informe;
    }

    /**
     * Devuelve todos los informes de la BD
     */
    public static function getAllInformes() {
        $consulta = "SELECT * FROM informes";
        $informes = null;

        self::new();
        if ($resultado = self::$conexion->query($consulta)) {
            self::closeDB();
            while ($fila = $resultado->fetch_assoc()) {
                $id = $fila['id'];
                $semana = $fila['semana'];
                $nInfectados = $fila['nInfectados'];
                $nFallecidos = $fila['nFallecidos'];
                $nAltas = $fila['nAltas'];
                $idAutor = $fila['idautor'];

                //Recupera el NOMBRE de la region
                self::new();
                $region = $fila['region'];
                $consultaRegion = 'SELECT nombre FROM regiones WHERE id=' . $region;
                $resultadoRegion = self::$conexion->query($consultaRegion);
                $fila = $resultadoRegion->fetch_assoc();
                $region = $fila['nombre'];
                self::closeDB();

                $informes[] = new Informe($id, $semana, $region, $nInfectados, $nFallecidos, $nAltas, $idAutor);
            }
            $resultado->free();
        }

        return $informes;
    }

    /**
     * Devuelve el NOMBRE de usuario por su id
     * @param type $id
     */
    public static function getNameById($id) {
        $nombre = null;

        self::new();
        $query = 'SELECT nombre FROM usuarios WHERE id=' . $id;
        $resultado = self::$conexion->query($query);
        if ($fila = $resultado->fetch_assoc()) {
            $nombre = $fila['nombre'];
        }
        self::closeDB();

        return $nombre;
    }

    /**
     * Actualiza un informe
     * @param type $id
     * @param type $nombre
     * @param type $correo
     * @param type $activo
     */
    public static function updateInforme($id, $nInfectados, $nFallecidos, $nAltas, $idAutor) {
        $sentencia = 'UPDATE informes SET nInfectados=' . $nInfectados . ', nFallecidos=' . $nFallecidos . ', nAltas=' . $nAltas . ', idautor=' . $idAutor . ' WHERE id=' . $id;

        self::new();
        self::$conexion->query($sentencia);
        self::closeDB();
    }

    /**
     * Devuelve un vector con todas las semanas
     */
    public static function getAllSemanas() {
        $semanas = null;
        $query = 'SELECT DISTINCT semana FROM informes';
        self::new();
        if ($resultado = self::$conexion->query($query)) {
            while ($fila = $resultado->fetch_assoc()) {
                $semanas[] = $fila['semana'];
            }
        }
        self::closeDB();
        return $semanas;
    }

    /**
     * Guarda una nueva region
     * @param type $nombre
     */
    public static function setRegion($nombre) {
        $query = 'INSERT INTO regiones VALUES(id,"' . $nombre . '")';
        self::new();
        self::$conexion->query($query);
        self::closeDB();
    }

    /**
     * Actualiza region
     * @param type $id
     * @param type $nombre
     */
    public static function updateRegion($id, $nombre) {
        $query = 'UPDATE regiones SET nombre="' . $nombre . '" WHERE id=' . $id;
        self::new();
        self::$conexion->query($query);
        self::closeDB();
    }

    /**
     * Devuelve todas las regiones
     */
    public static function getAllRegiones() {
        $consulta = "SELECT * FROM regiones";
        $regiones = null;

        self::new();
        if ($resultado = self::$conexion->query($consulta)) {
            self::closeDB();
            while ($fila = $resultado->fetch_assoc()) {
                $id = $fila['id'];
                $nombre = $fila['nombre'];

                $regiones[] = new Region($id, $nombre);
            }
            $resultado->free();
        }

        return $regiones;
    }

    /**
     * Devuelve todas las regiones que estén siendo utilizadas por algún informe
     */
    public static function getAllUniqueRegiones() {
        $consulta = 'SELECT * FROM regiones WHERE id IN (SELECT DISTINCT region FROM informes)';
        $regiones = null;

        self::new();
        if ($resultado = self::$conexion->query($consulta)) {
            self::closeDB();
            while ($fila = $resultado->fetch_assoc()) {
                $id = $fila['id'];
                $nombre = $fila['nombre'];

                $regiones[] = new Region($id, $nombre);
            }
            $resultado->free();
        }

        return $regiones;
    }

    /**
     * Elimina una region
     * @param type $id
     */
    public static function deleteRegion($id) {
        $query = 'DELETE FROM regiones WHERE id=' . $id;
        self::new();
        self::$conexion->query($query);
        self::closeDB();

        $query = 'UPDATE informes SET region=0 WHERE region=' . $id;
        self::new();
        self::$conexion->query($query);
        self::closeDB();
    }

    /**
     * Elimina un usuario
     * @param type $id
     */
    public static function deleteUser($id) {
        $query = 'DELETE FROM usuarios WHERE id=' . $id;
        self::new();
        self::$conexion->query($query);
        self::closeDB();
    }

}
