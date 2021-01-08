<?php
//COMPRUEBA EL DIRECTORIO ACTUAL PARA ESTABLECER UNA RUTA ADECUADA A LOS ENLACES
$dir = $_SERVER['PHP_SELF'];

if (substr($dir, -9) == 'index.php') {
//Estamos en inicio
    $ruta = '';
} else {
//NO estamos en inicio
    $ruta = '../';
}

//IMPORTA LAS CLASES NECESARIAS E INICIA LA SESIÓN
require_once $ruta . 'modelo/Usuario.php';

session_start();

//---------------COMPRUEBA SI EL USUARIO HA INICIADO SESIÓN Y LO GUARDA
if (isset($_SESSION['usuarioIniciado'])) {
    $usuarioIniciado = $_SESSION['usuarioIniciado'];
}
?>

<!--Cabecera-->
<nav class = "navbar navbar-expand navbar-light bg-light w-100">
    <a class = "navbar-brand" href = "<?php echo $ruta . 'index.php'; ?>">
        <img src = "<?php echo $ruta . 'images/logo.png'; ?>" width = "100" height = "auto" alt = "">
    </a>
    <div class = "collapse navbar-collapse" id = "navbarNavDropdown">
        <ul class = "navbar-nav">
            <li class = "nav-item active">
                <a class = "nav-link" href = "<?php echo $ruta . 'index.php'; ?>">Inicio <span class = "sr-only">(current)</span></a>
            </li>
        </ul>
        <div class = "dropdown ml-auto">
            <a class = "nav-link dropdown-toggle desplegable" href = "#" id = "navbarDropdownMenuLink" data-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "false">
                Perfil
            </a>
            <div class = "dropdown-menu" aria-labelledby = "navbarDropdownMenuLink">
                <a class = "dropdown-item" href = "#">Iniciar sesión</a>
                <a class = "dropdown-item" href = "<?php echo $ruta . 'vistas/registro.php'; ?>">Crear cuenta</a>
            </div>
        </div>
    </div>
</nav>

<?php
//MUESTRA UN MENSAJE DE ALERTA/ERROR SI LO HUBIERA EN LA SESIÓN
if (isset($_SESSION['mensaje'])) {
    ?>
    <div class = "col-12 bg-warning">
        <?php echo $_SESSION['mensaje']; ?>
    </div>
    <?php
    unset($_SESSION['mensaje']);
}
?>