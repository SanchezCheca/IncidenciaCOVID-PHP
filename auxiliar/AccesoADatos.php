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
        $idUsuario = self::getIdByorreo($correo);

        self::new();
        $query = 'INSERT INTO usuario_rol VALUES(' . $idUsuario . ', ' . $rol . ')';
        self::$conexion->query($query);
        self::closeDB();
    }

    public static function getIdByorreo($correo) {
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
            $rol = $fila['rol'];
            $correo = $fila['correo'];
            $nombre = $fila['nombre'];
            $activo = $fila['activo'];

            $passEncriptada = $fila['pass'];

            //COMPRUEBA QUE LA CONTRASEÑA SEA CORRECTA
            if (hash_equals($passEncriptada, crypt($pass, $passEncriptada))) {
                //Contraseña correcta, carga las aulas del alumno/profesor
                $aulas = null;
                if ($rol == 0) {
                    //El usuario es un alumno
                    $consultaAulas = 'SELECT * FROM aulas WHERE id IN (SELECT idAula FROM aula_alumno WHERE idAlumno = ' . $id . ')';

                    if ($resultadoAulas = self::$conexion->query($consultaAulas)) {
                        while ($filaAula = $resultadoAulas->fetch_assoc()) {
                            $idAula = $filaAula['id'];
                            $idProfesor = $filaAula['idProfesor'];
                            $nombreAula = $filaAula['nombre'];

                            //Recupera el NOMBRE del profesor a cargo
                            $consultaExtra = 'SELECT nombre FROM usuarios WHERE id=' . $idProfesor;
                            $resultadoExtra = self::$conexion->query($consultaExtra);
                            $nombreProfesor = null;
                            if ($filaExtra = $resultadoExtra->fetch_assoc()) {
                                $nombreProfesor = $filaExtra['nombre'];
                            }

                            $aula = new Aula($idAula, $nombreAula, $idProfesor, $nombreProfesor);
                            $aulas[] = $aula;
                        }
                    }
                } else {
                    //El usuario es un profesor
                    $consultaAulas = 'SELECT * FROM aulas WHERE idProfesor = ' . $id;
                    $resultadoAulas = self::$conexion->query($consultaAulas);

                    while ($filaAula = $resultadoAulas->fetch_assoc()) {
                        $idAula = $filaAula['id'];
                        $nombreAula = $filaAula['nombre'];

                        $aula = new Aula($idAula, $nombreAula, $id, $nombre);
                        $aulas[] = $aula;
                    }
                }

                $usuario = new Usuario($id, $rol, $correo, $nombre, $activo, $aulas);
            }
        }
        $result->free();

        self::closeDB();

        return $usuario;
    }

    /**
     * Devuelve un usuario a partir de su id
     * @param type $id
     * @return \Usuario
     */
    public static function getUserById($id) {
        $usuario = null;

        self::new();
        $consulta = "SELECT * FROM usuarios WHERE id=" . $id;
        $resultado = self::$conexion->query($consulta);

        if ($fila = $resultado->fetch_assoc()) {
            $id = $fila['id'];
            $rol = $fila['rol'];
            $nombre = $fila['nombre'];
            $correo = $fila['correo'];

            //Tareas asignadas al usuario
            $tareas = null;
            $consultaTareas = 'SELECT * FROM tarea_usuario WHERE idUsuario=' . $id;
            $resultadoTareas = self::$conexion->query($consultaTareas);
            while ($fila = $resultadoTareas->fetch_assoc()) {
                $tareas[] = $fila['idTarea'];
            }

            $usuario = new Usuario($id, $rol, $nombre, $correo, $tareas);
        }
        $resultadoTareas->free();
        $resultado->free();
        self::closeDB();

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
            while ($fila = $resultado->fetch_assoc()) {
                $nombre = $fila['nombre'];
                $correo = $fila['correo'];
                $rol = $fila['rol'];
                $id = $fila['id'];
                $usuarios[] = new Usuario($id, $rol, $nombre, $correo, null);
            }

            $resultado->free();
        }
        self::closeDB();

        return $usuarios;
    }

    /**
     * Actualiza el nombre, correo y rol de un usuario cuyo id recibe
     * @param type $id
     * @param type $nombre
     * @param type $correo
     * @param type $rol
     */
    public static function userUpdate($id, $nombre, $correo, $rol) {
        $sentencia = "UPDATE usuarios SET correo='" . $correo . "', nombre='" . $nombre . "', rol=" . $rol . " WHERE id='" . $id . "';";

        self::new();
        self::$conexion->query($sentencia);
        self::closeDB();
    }

    /**
     * Elimina el usuario definido por su id
     * @param type $id
     */
    public static function userDrop($id) {
        $sentencia = 'DELETE FROM usuarios WHERE id="' . $id . '";';

        self::new();
        self::$conexion->query($sentencia);
        self::closeDB();
    }

    /**
     * Inserta una nueva tarea en la BD
     */
    public static function insertTask($nombre, $descripcion, $proceso, $nivel) {
        $resultado = true;

        self::new();
        $query = 'INSERT INTO tareas VALUES(id, "' . $nombre . '", "' . $descripcion . '", ' . $proceso . ', "' . $nivel . '", 0)';

        if (!self::$conexion->query($query)) {
            $resultado = "Error al insertar: " . mysqli_error(self::$conexion) . '<br/>';
        }
        self::closeDB();

        return $resultado;
    }

    /**
     * Devuelve la tarea definida por su id
     * @param type $id
     */
    public static function getTask($id) {
        $tarea = null;

        self::new();
        $consulta = "SELECT * FROM tareas WHERE id=" . $id;
        $resultado = self::$conexion->query($consulta);
        if ($fila = $resultado->fetch_assoc()) {
            $nombre = $fila['nombre'];
            $descripcion = $fila['descripcion'];
            $proceso = $fila['proceso'];
            $nivel = $fila['nivel'];
            $terminada = $fila['terminada'];

            //Usuarios asignados a la tarea
            $usuariosAsignados = null;
            $consultaUsuarios = 'SELECT * FROM usuarios WHERE id IN (SELECT idUsuario FROM tarea_usuario WHERE idTarea = ' . $id . ')';
            if ($resultadoUsuarios = self::$conexion->query($consultaUsuarios)) {
                while ($fila = $resultadoUsuarios->fetch_assoc()) {
                    $nombreUsu = $fila['nombre'];
                    $correo = $fila['correo'];
                    $rol = $fila['rol'];
                    $idUsu = $fila['id'];

                    $usuariosAsignados[] = new Usuario($idUsu, $rol, $nombreUsu, $correo, null);
                }
            }

            $tarea = new Tarea($id, $nombre, $descripcion, $proceso, $nivel, $terminada, $usuariosAsignados);
        }

        $resultadoUsuarios->free();
        $resultado->free();
        self::closeDB();

        return $tarea;
    }

    /**
     * Devuelve un vector asociativo con todas las tareas de la BD
     */
    public static function getAllTasks() {
        $consulta = 'SELECT * FROM tareas';
        $tareas = null;

        self::new();
        if ($resultado = self::$conexion->query($consulta)) {
            while ($fila = $resultado->fetch_assoc()) {
                $id = $fila['id'];
                $nombre = $fila['nombre'];
                $descripcion = $fila['descripcion'];
                $proceso = $fila['proceso'];
                $nivel = $fila['nivel'];
                $terminada = $fila['terminada'];

                //Recoge los usuarios asignados a la tarea
                $usuariosAsignados = null;
                $consultaUsuarios = 'SELECT * FROM usuarios WHERE id IN (SELECT idUsuario FROM tarea_usuario WHERE idTarea = ' . $id . ')';
                if ($resultadoUsuarios = self::$conexion->query($consultaUsuarios)) {
                    while ($fila = $resultadoUsuarios->fetch_assoc()) {
                        $nombreUsu = $fila['nombre'];
                        $correo = $fila['correo'];
                        $rol = $fila['rol'];
                        $idUsu = $fila['id'];

                        $usuariosAsignados[] = new Usuario($idUsu, $rol, $nombreUsu, $correo, null);
                    }
                }

                $tareas[] = new Tarea($id, $nombre, $descripcion, $proceso, $nivel, $terminada, $usuariosAsignados);
            }
            $resultadoUsuarios->free();
            $resultado->free();
        }
        self::closeDB();

        return $tareas;
    }

    /**
     * Actualiza la información de una tarea
     * @param type $idTarea
     * @param type $nombre
     * @param type $descripcion
     * @param type $proceso
     * @param type $nivel
     */
    public static function taskUpdate($idTarea, $nombre, $descripcion, $proceso, $nivel) {
        $sentencia = "UPDATE tareas SET nombre='" . $nombre . "', descripcion='" . $descripcion . "', proceso=" . $proceso . ", nivel='" . $nivel . "' WHERE id=" . $idTarea;

        self::new();
        self::$conexion->query($sentencia);
        self::closeDB();
    }

    /**
     * Devuelve el id de la última tarea añadida. Necesario para añadir automáticamente a tareas recién creadas.
     */
    public static function getLastTaskId() {
        self::new();
        $consulta = 'SELECT id FROM tareas ORDER BY id DESC LIMIT 1';
        $resultado = self::$conexion->query($consulta);

        if ($fila = $resultado->fetch_assoc()) {
            $id = $fila['id'];
        }

        self::closeDB();
        return $id;
    }

    /**
     * Asigna un usuario a una tarea
     * @param type $usuario
     * @param type $tarea
     */
    public static function assign($idTarea, $idUsuario) {
        $resultado = true;

        self::new();
        $query = 'INSERT INTO tarea_usuario VALUES(' . $idTarea . ',' . $idUsuario . ')';

        if (!self::$conexion->query($query)) {
            $resultado = "Error al insertar: " . mysqli_error(self::$conexion) . '<br/>';
        }
        self::closeDB();

        return $resultado;
    }

    /**
     * Quita a un usuario de la tarea asignada
     * @param type $idTarea
     * @param type $idUsuario
     */
    public static function unAssign($idTarea, $idUsuario) {
        $sentencia = 'DELETE FROM tarea_usuario WHERE idTarea=' . $idTarea . ' AND idUsuario=' . $idUsuario;

        self::new();
        self::$conexion->query($sentencia);
        self::closeDB();
    }

}
