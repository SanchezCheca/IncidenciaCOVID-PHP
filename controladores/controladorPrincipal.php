<?php

require_once '../auxiliar/AccesoADatos.php';
require_once '../modelo/Usuario.php';

session_start();

/**
 * Viene del formulario de registro, comprueba que no existe y lo inserta en la BD
 */
if (isset($_REQUEST['registro'])) {
    $nombre = $_REQUEST['nombre'];
    $correo = $_REQUEST['correo'];
    $pass = $_REQUEST['pass'];

    if (AccesoADatos::isUser($correo)) {
        $_SESSION['mensaje'] = 'ERROR: El correo ya está registrado.';
    } else {
        if ($resultado = AccesoADatos::insertUser($correo, $nombre, $pass)) {
            $mensaje = 'Se ha creado al usuario <i> ' . $nombre . '</i>. Debes esperar a que un administrador active tu cuenta.';
        } else {
            $mensaje = 'ERROR: ' . $resultado;
        }
        $_SESSION['mensaje'] = $mensaje;
    }

    header('Location: ../vistas/registro.php');
}

/**
 * Viene del formulario de inicio de sesión, comprueba que es correcto y guarda el usuario iniciado en la sesión
 */
if (isset($_REQUEST['inicioSesion'])) {
    $correo = $_REQUEST['correo'];
    $pass = $_REQUEST['pass'];

    if ($usuario = AccesoADatos::getUsuario($correo, $pass)) {
        if ($usuario->getActivo() == 1) {
            $_SESSION['usuarioIniciado'] = $usuario;
            $mensaje = 'Has iniciado sesión como ' . $usuario->getNombre() . '.';
        } else {
            $mensaje = 'Tu cuenta aún no ha sido activada. Debes esperar a que un administrador la active';
        }
    } else {
        $mensaje = 'ERROR: Correo y/o contraseña incorrectos';
    }
    $_SESSION['mensaje'] = $mensaje;
    header('Location: ../index.php');
}

/**
 * Cierra la sesión
 */
if (isset($_REQUEST['cerrarSesion'])) {
    unset($_SESSION['usuarioIniciado']);
    $_SESSION['mensaje'] = 'Has cerrado la sesión.';
    header('Location: ../index.php');
}

/**
 * Viene de 'Administrar usuarios', carga, si es administrador, una lista con todos los usuarios
 */
if (isset($_REQUEST['administrarUsuarios'])) {
    if (isset($_SESSION['usuarioIniciado'])) {
        $usuario = $_SESSION['usuarioIniciado'];
        if ($usuario->isAdmin()) {
            $usuarios = AccesoADatos::getAllUsers();
            $_SESSION['usuarios'] = $usuarios;
            header('Location: ../vistas/crud.php');
        } else {
            $_SESSION['mensaje'] = 'No tienes permiso para ver esta página.';
            header('Location: ../index.php');
        }
    } else {
        $_SESSION['mensaje'] = 'Ha ocurrido algún error.';
        header('Location: ../index.php');
    }
}

/**
 * Viene de 'Administrar regiones', las carga y manda a la vista
 */
if (isset($_REQUEST['administrarRegiones'])) {
    if (isset($_SESSION['usuarioIniciado'])) {
        $usuario = $_SESSION['usuarioIniciado'];
        if ($usuario->isAdmin()) {
            $regiones = AccesoADatos::getAllRegiones();
            $_SESSION['regiones'] = $regiones;
            header('Location: ../vistas/regiones.php');
        } else {
            $_SESSION['mensaje'] = 'No tienes permiso para ver esta página.';
            header('Location: ../index.php');
        }
    } else {
        $_SESSION['mensaje'] = 'Ha ocurrido algún error.';
        header('Location: ../index.php');
    }
}