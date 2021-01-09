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
require_once $ruta . 'modelo/Informe.php';

session_start();

//---------------COMPRUEBA SI EL USUARIO HA INICIADO SESIÓN Y LO GUARDA
if (isset($_SESSION['usuarioIniciado'])) {
    $usuarioIniciado = $_SESSION['usuarioIniciado'];
}
?>

<!--Cabecera-->
<nav class = "navbar navbar-expand navbar-light bg-light w-100">
    <a class = "navbar-brand" href = "<?php echo $ruta . 'index.php'; ?>">
        <img src = "<?php echo $ruta . 'images/logo.png'; ?>" width = "100" height = "auto" alt = "logo inCOVID">
    </a>
    <div class = "collapse navbar-collapse" id = "navbarNavDropdown">
        <ul class = "navbar-nav">
            <li class = "nav-item <?php
            if (substr($dir, -9) == 'index.php') {
                echo 'active';
            }
            ?>">
                <a class = "nav-link" href = "<?php echo $ruta . 'index.php'; ?>">Todos los informes</a>
            </li>
            <?php
            //Muestra el enlace 'Crear informe' si el usuario iniciado es autor
            if (isset($usuarioIniciado) && $usuarioIniciado->isAutor()) {
                ?>
                <li class="nav-item <?php
                if (substr($dir, -16) == 'crearInforme.php') {
                    echo 'active';
                }
                ?>">
                    <a class="nav-link" href="<?php echo $ruta . 'vistas/crearInforme.php'; ?>">+Crear informe</a>
                </li>
                <?php
            }
            ?>
        </ul>
        <div class = "dropdown ml-auto">
            <a class = "nav-link dropdown-toggle desplegable" href = "#" id = "navbarDropdownMenuLink" data-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "false">
                <?php
                if (isset($usuarioIniciado)) {
                    echo '<i>' . $usuarioIniciado->getNombre() . '</i>';
                } else {
                    echo 'Perfil';
                }
                ?>
            </a>
            <div class = "dropdown-menu" aria-labelledby = "navbarDropdownMenuLink">
                <?php
                if (isset($usuarioIniciado)) {
                    //Ha iniciado sesión, se muestran otras opciones
                    ?>
                    <a class="dropdown-item" href="#">Mi perfil</a>
                    <form name="menu" action="<?php echo $ruta . 'controladores/controladorPrincipal.php'; ?>" method="POST">
                        <?php
                        if ($usuarioIniciado->isAdmin()) {
                            ?>
                            <input type="submit" name="administrarUsuarios" class="dropdown-item" value="Administrar usuarios">
                            <?php
                        }
                        ?>
                        <input type="submit" name="cerrarSesion" class="dropdown-item" value="Cerrar sesión">
                    </form>
                    <?php
                } else {
                    //No ha iniciado sesión, se muestran la opción de inicio y registro
                    ?>
                    <a class = "dropdown-item" href = "<?php echo $ruta . 'vistas/login.php'; ?>">Iniciar sesión</a>
                    <a class = "dropdown-item" href = "<?php echo $ruta . 'vistas/registro.php'; ?>">Crear cuenta</a>
                    <?php
                }
                ?>
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