<?php

require_once '../auxiliar/AccesoADatos.php';
require_once '../modelo/Informe.php';

session_start();

/**
 * Crear un nuevo informe
 */
if (isset($_REQUEST['crearInforme'])) {
    $semana = $_REQUEST['semana'];
    $region = $_REQUEST['region'];
    $nInfectados = $_REQUEST['nInfectados'];
    $nFallecidos = $_REQUEST['nFallecidos'];
    $nAltas = $_REQUEST['nAltas'];

    $mensaje = '';

    //Comprueba que no exista un informe para esa semana y esa región
    if (!AccesoADatos::isInforme($semana, $region)) {
        //Recupera el id del usuario iniciado
        if (isset($_SESSION['usuarioIniciado'])) {
            $usuarioIniciado = $_SESSION['usuarioIniciado'];
            $idAutor = $usuarioIniciado->getId();
            AccesoADatos::insertInforme($semana, $region, $nInfectados, $nFallecidos, $nAltas, $idAutor);
            $mensaje = 'Se ha creado el informe para la semana [' . $semana . '] y la región [' . $region . '].';
        } else {
            $mensaje = 'Ha ocurrido algún error.';
        }
    } else {
        $mensaje = 'ERROR: Ya existe un informe en esa fecha y para esa región.';
    }

    $_SESSION['mensaje'] = $mensaje;
    header('Location: ../vistas/crearInforme.php');
}

/**
 * Viene de 'ver informe' en la página de inicio
 * Carga el informe de la BD y lo guarda en la sesión
 */
if (isset($_REQUEST['verInforme'])) {
    $id = $_REQUEST['id'];
    
    //Recupera el informe
    $informe = AccesoADatos::getInforme($id);
    $_SESSION['informe'] = $informe;
    
    //Recupera el nombre del autor
    $nombreAutor = AccesoADatos::getNameById($informe->getIdAutor());
    $_SESSION['nombreAutor'] = $nombreAutor;
    
    header('Location: ../vistas/verInforme.php');
}

/**
 * Viene de 'editar informe'
 */
if (isset($_REQUEST['actualizarInforme'])) {
    $id = $_REQUEST['id'];
    $nInfectados = $_REQUEST['nInfectados'];
    $nFallecidos = $_REQUEST['nFallecidos'];
    $nAltas = $_REQUEST['nAltas'];
    
    if (isset($_SESSION['usuarioIniciado'])) {
        $usuarioIniciado = $_SESSION['usuarioIniciado'];
        $idAutor = $usuarioIniciado->getId();
        
        AccesoADatos::updateInforme($id, $nInfectados, $nFallecidos, $nAltas, $idAutor);
        $mensaje = 'Se ha actualizado el informe.';
        
        //Actualiza el informe en la sesión
        //$_SESSION['informe'] = AccesoADatos::getInforme($id);
    } else {
        $mensaje = 'Ha ocurrido algún error';
    }
    
    $_SESSION['mensaje'] = $mensaje;
    header('Location: ../vistas/verInforme.php');
}