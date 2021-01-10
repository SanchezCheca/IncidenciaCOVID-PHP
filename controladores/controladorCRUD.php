<?php

require_once '../auxiliar/AccesoADatos.php';
require_once '../modelo/Usuario.php';

session_start();

/**
 * Controlador se encarga de las funcionalidades relacionadas con el Administrador
 * crud.php
 * regiones.php
 */
/**
 * Actualiza en BD los datos que vienen del crud
 */
if (isset($_REQUEST['actualizarUsuario'])) {
    $id = $_REQUEST['id'];
    $nombre = $_REQUEST['nombre'];
    $correo = $_REQUEST['correo'];

    $admin = false;
    if (isset($_REQUEST['admin'])) {
        $admin = true;
    }
    
    $autor = false;
    if (isset($_REQUEST['autor'])) {
        $autor = false;
    }

    $activo = 0;
    if (isset($_REQUEST['activo'])) {
        $activo = 1;
    }

    //Comprueba si es administrador
    $esAdmin = false;
    $rolesUsuario = AccesoADatos::getRolById($id);
    foreach ($rolesUsuario as $rol) {
        if ($rol == 1) {
            $esAdmin = true;
        }
    }

    //Comprueba si es autor
    $esAutor = false;
    foreach ($rolesUsuario as $rol) {
        if ($rol == 0) {
            $esAutor = true;
        }
    }

    //Comprueba si se ha cambiado el correo del usuario que se está modificando y no se ha repetido en la BD
    if (AccesoADatos::getCorreoById($id) != $correo && AccesoADatos::isUser($correo)) {
        //Ha cambiado el correo de un usuario por otro que ya existe en la BD
        $_SESSION['mensaje'] = 'ERROR: El correo ya está siendo utilizado por otro usuario.';
        header('Location: ../vistas/crud.php');
    } else {
        //Elimina o inserta el rol '1' (administrador)
        if ($esAdmin && !$admin) {
            AccesoADatos::removeRol($id, 1);
        } else if (!$esAdmin && $admin) {
            AccesoADatos::insertRolById($id, 1);
        }
        
        //Elimina o inserta el rol '0' (autor)
        if ($esAutor && !$autor) {
            AccesoADatos::removeRol($id, 0);
        } else if (!$esAutor && $autor) {
            AccesoADatos::insertRolById($id, 0);
        }

        //Actualiza los datos del usuario
        AccesoADatos::updateUser($id, $nombre, $correo, $activo);

        $_SESSION['mensaje'] = 'Se ha actualizado el usuario con ID: ' . $id;

        //Manda al controlador principal con el dato 'administrarUsuarios' por GET para que actualice la lista de usuarios del CRUD
        header('Location: ../controladores/controladorPrincipal.php?administrarUsuarios=1');
    }
}