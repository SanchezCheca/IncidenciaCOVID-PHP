<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Administración de usuarios - inCOVID</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
              integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
                integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>

        <!-- Estilos -->
        <link rel="stylesheet" href="../estilos/misEstilos.css">

    </head>
    <body>
        <div class="container-fluid d-flex justify-content-center">
            <div class="row principal">

                <?php include '../recursos/cabecera.php'; ?>

                <!-- Título de la sección -->
                <div class="col-12 mt-4 ml-4">
                    <h3 class="h3">Panel de administrador</h3>
                </div>

                <!-- Cuerpo -->
                <div class="col-12 mt-4 px-4 d-flex justify-content-center">
                    <table class="table table-hover w-75" style="text-align: center">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Correo</th>
                                <th scope="col">¿Admin?</th>
                                <th scope="col">Activo</th>
                                <th colspan="2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_SESSION['usuarios'])) {
                                $usuarios = $_SESSION['usuarios'];
                                foreach ($usuarios as $usuario) {
                                    ?>
                                    <tr>
                                <form name="administracionUsuario" action="../controladores/controladorCRUD.php" method="POST">
                                    <th scope="row"><?php echo $usuario->getId(); ?></th>
                                    <input type="hidden" name="id" value="<?php echo $usuario->getId(); ?>">
                                    <td><input class="form-control" type="text" name="nombre" value="<?php echo $usuario->getNombre(); ?>"></td>
                                    <td><input class="form-control" type="email" name="correo" value="<?php echo $usuario->getCorreo(); ?>"></td>
                                    <td><input type="checkbox" name="admin" value="1" <?php
                                        if ($usuario->isAdmin()) {
                                            echo 'checked';
                                        };
                                        ?>></td>
                                    <td><input type="checkbox" name="activo" <?php
                                        if ($usuario->getActivo() == 1) {
                                            echo 'checked';
                                        }
                                        ?>></td>
                                    <td><input type="submit" class="btn btn-success" name="actualizarUsuario" value="Guardar"></td>
                                    <td><input type="submit" class="btn btn-danger" name="eliminarUsuario" value="Eliminar"></td>
                                </form>
                                </tr>
                                <?php
                            }
                        } else {
                            $_SESSION['mensaje'] = 'Ha ocurrido algún error.';
                            header('Location: ../index.php');
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </body>
</html>
