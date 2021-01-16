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
            <div class="row principal rounded">

                <?php
                include '../recursos/cabecera.php';
                if (!(isset($usuarioIniciado) && $usuarioIniciado->isAdmin())) {
                    $_SESSION['mensaje'] = 'No tienes permiso para acceder a esta página';
                    header('Location: ../index.php');
                }
                ?>

                <!-- Título de la sección -->
                <div class="col-12 mt-4 ml-4">
                    <h4 class="h4">Crear región</h4>
                </div>

                <div class="col-12 mt-4 px-4 d-flex justify-content-center">
                    <form name="administracionRegion" action="../controladores/controladorCRUD.php" method="POST">
                        <div class="form-inline w-100">
                            <input type="text" name="nombre" placeholder="Nombre de la región" class="form-control">
                            <input type="submit" name="crearRegion" value="Crear región" class="btn btn-success">
                        </div>

                    </form>
                </div>

                <!-- Título de la sección -->
                <div class="col-12 mt-4 ml-4">
                    <h4 class="h4">Todas las regiones</h4>
                </div>

                <!-- Cuerpo -->
                <div class="col-12 mt-4 px-4 d-flex justify-content-center">
                    <table class="table table-hover w-75" style="text-align: center">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th colspan="2" scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_SESSION['regiones'])) {
                                $regiones = $_SESSION['regiones'];
                                foreach ($regiones as $region) {
                                    if ($region->getId() == 0) {
                                        continue;
                                    }
                                    ?>
                                    <tr>
                                <form name="administracionRegion" action="../controladores/controladorCRUD.php" method="POST">
                                    <th scope="row"><?php echo $region->getId(); ?></th>
                                    <input type="hidden" name="id" value="<?php echo $region->getId(); ?>">
                                    <td><input class="form-control" type="text" name="nombre" value="<?php echo $region->getNombre(); ?>"></td>

                                    <td><input type="submit" class="btn btn-success" name="actualizarRegion" value="Guardar"></td>
                                    <td><input type="submit" class="btn btn-danger" name="eliminarRegion" value="Eliminar"></td>
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
                <?php include '../recursos/footer.php'; ?>
            </div>

        </div>
    </body>
</html>
