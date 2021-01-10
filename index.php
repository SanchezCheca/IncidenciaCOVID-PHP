<!DOCTYPE html>
<html lang="es_ES">
    <head>
        <meta charset="UTF-8">
        <title>Inicio - inCOVID</title>

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
        <link rel="stylesheet" href="estilos/misEstilos.css">

    </head>
    <body>
        <div class="containter d-flex justify-content-center">
            <div class="row principal rounded">

                <?php
                include 'recursos/cabecera.php';
                require_once 'modelo/Informe.php';
                require_once 'auxiliar/AccesoADatos.php';
                $informes = AccesoADatos::getAllInformes();
                $regiones = AccesoADatos::getAllRegiones();
                $semanas = AccesoADatos::getAllSemanas();

                //Calcula los datos totales
                $infectados = 0;
                $fallecidos = 0;
                $altas = 0;
                foreach ($informes as $informe) {
                    $infectados += $informe->getNInfectados();
                    $fallecidos += $informe->getNFallecidos();
                    $altas += $informe->getNAltas();
                }
                ?>

                <!-- Filtro -->
                <div class="col-2 mt-4">
                    <h5 class="h5">Filtrar por:</h5>
                </div>
                <div class="col-8 mt-4">
                    <form name="filtro" action="controladores/controladorPrincipal.php" method="POST">
                        <div class="form-group">
                            <label for="region">Región: </label>
                            <select class="form-control" name="region">
                                <option value="TODAS">TODAS</option>
                                <?php
                                foreach ($regiones as $region) {
                                    ?>
                                    <option value="<?php echo $region->getNombre(); ?>"><?php echo $region->getNombre(); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <label for="semana">Semana: </label>
                            <select class="form-control" name="semana">
                                <option value="TODAS">TODAS</option>
                                <?php
                                foreach ($semanas as $semana) {
                                    ?>
                                    <option value="<?php echo $semana; ?>"><?php echo $semana; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>


                </div>
                <div class="col-2 mt-5">
                    <input class="btn btn-dark" type="submit" name="filtrar" value="Aplicar">
                    </form>
                </div>



                <!-- Primera seccion -->
                <div class="col-12 mt-4 ml-4">
                    <h4 class="h4">Datos totales</h4>
                </div>

                <div class="col-12 mt-4 px-4 d-flex justify-content-center">
                    <table class="table table-hover w-75">
                        <thead>
                            <tr>
                                <th scope="col">Infectados</th>
                                <th scope="col">Fallecidos</th>
                                <th scope="col">Altas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $infectados; ?></td>
                                <td><?php echo $fallecidos; ?></td>
                                <td><?php echo $altas; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Título de la sección -->
                <div class="col-12 mt-4 ml-4">
                    <h4 class="h4">Todos los informes</h4>
                </div>

                <!-- Cuerpo -->
                <div class="col-12 mt-4 px-4 d-flex justify-content-center">
                    <table class="table table-hover w-75">
                        <thead>
                            <tr>
                                <th scope="col">Semana</th>
                                <th scope="col">Región</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($informes as $informe) {
                                ?>
                                <tr>
                                    <td><?php echo $informe->getSemana(); ?></td>
                                    <td><?php echo $informe->getRegion(); ?></td>
                                    <td>
                                        <form name="verInforme" action="controladores/controladorInformes.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $informe->getId(); ?>">
                                            <input type="submit" class="btn btn-primary" name="verInforme" value="Ver informe">
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <?php include 'recursos/footer.php'; ?>
            </div>

        </div>


    </body>
</html>
