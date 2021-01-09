<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Informe - inCOVID</title>

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

                <?php
                //Comprueba si se está editando el informe para mostrar la vista adecuada
                if (isset($_REQUEST['editarInforme'])) {
                    ?>
                    <!-- Título de la sección -->
                    <div class="col-12 mt-4 ml-4">
                        <h3 class="h3">Editar informe</h3>
                    </div>

                    <!-- Cuerpo -->
                    <div class="col-12 mt-4 px-4 d-flex justify-content-center">
                        <table class="table table-hover" style="text-align: center">
                            <thead>
                                <tr>
                                    <th scope="col">Semana</th>
                                    <th scope="col">Región</th>
                                    <th scope="col">Nº de infectados</th>
                                    <th scope="col">Nº de fallecidos</th>
                                    <th scope="col">Nº de altas</th>
                                    <th colspan="2" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <form name="editarInforme" action="../controladores/controladorInformes.php" method="POST">
                                <?php
                                if (isset($_SESSION['informe']) && isset($_SESSION['nombreAutor'])) {
                                    $informe = $_SESSION['informe'];
                                    $nombreAutor = $_SESSION['nombreAutor'];
                                    ?>
                                    <tr>
                                        <td><?php echo $informe->getSemana(); ?></td>
                                        <td><?php echo $informe->getRegion(); ?></td>
                                        <td><input type="number" name="nInfectados" value="<?php echo $informe->getNInfectados(); ?>"></td>
                                        <td><input type="number" name="nFallecidos" value="<?php echo $informe->getNFallecidos(); ?>"></td>
                                        <td><input type="number" name="nAltas" value="<?php echo $informe->getNAltas(); ?>"></td>
                                    <input type="hidden" name="id" value="<?php echo $informe->getId(); ?>">
                                    <td><input type="submit" name="actualizarInforme" value="Guardar" class="btn btn-success"></td>
                                </form>
                                <td><a href="verInforme.php"><button class="btn btn-warning">Cancelar</button></a></td>
                                </tr>
                                <?php
                            } else {
                                $_SESSION['mensaje'] = 'Ha ocurrido algún error.';
                                header('Location: ../index.php');
                            }
                            ?>

                            </tbody>
                        </table>
                    </div>
                    <?php
                } else {
                    ?>
                    <!-- Título de la sección -->
                    <div class="col-12 mt-4 ml-4">
                        <h3 class="h3">Ver informe</h3>
                    </div>

                    <!-- Cuerpo -->
                    <div class="col-12 mt-4 px-4 d-flex justify-content-center">
                        <table class="table table-hover w-75" style="text-align: center">
                            <thead>
                                <tr>
                                    <th scope="col">Semana</th>
                                    <th scope="col">Región</th>
                                    <th scope="col">Nº de infectados</th>
                                    <th scope="col">Nº de fallecidos</th>
                                    <th scope="col">Nº de altas</th>
                                    <th scope="col">Autor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($_SESSION['informe']) && isset($_SESSION['nombreAutor'])) {
                                    $informe = $_SESSION['informe'];
                                    $nombreAutor = $_SESSION['nombreAutor'];
                                    ?>
                                    <tr>
                                        <td><?php echo $informe->getSemana(); ?></td>
                                        <td><?php echo $informe->getRegion(); ?></td>
                                        <td><?php echo $informe->getNInfectados(); ?></td>
                                        <td><?php echo $informe->getNFallecidos(); ?></td>
                                        <td><?php echo $informe->getNAltas(); ?></td>
                                        <td><a href="verPerfil.php?id=<?php echo $informe->getIdAutor(); ?>"><?php echo $nombreAutor; ?></a></td>
                                    </tr>
                                    <?php
                                } else {
                                    $_SESSION['mensaje'] = 'Ha ocurrido algún error.';
                                    header('Location: ../index.php');
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    //Si el usuario iniciado es autor da la opción de editar
                    if (isset($usuarioIniciado) && $usuarioIniciado->isAutor()) {
                        ?>
                        <div class="col-12 d-flex justify-content-center">
                            <form name="editar" action="verInforme.php" method="POST">
                                <input type="submit" class="btn btn-secondary" name="editarInforme" value="EDITAR">
                            </form>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                }
                ?>
            </div>
        </div>
    </body>
</html>
