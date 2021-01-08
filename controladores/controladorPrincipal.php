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
            $mensaje = 'Usuario creado con éxito. Debes esperar a que un administrador active tu cuenta.';
        } else {
            $mensaje = 'ERROR: ' . $resultado;
        }
        $_SESSION['mensaje'] = $mensaje;
    }

    header('Location: ../vistas/registro.php');
}