<!DOCTYPE html>
<html lang="es_ES">
    <head>
        <meta charset="UTF-8">
        <title>Inicio1 - inCOVID</title>

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
                $regiones = AccesoADatos::getAllUniqueRegiones();
                $semanas = AccesoADatos::getAllSemanas();

                if (isset($_REQUEST['filtrar'])) {
                    $filtroRegion = $_REQUEST['filtroRegion'];
                    $filtroSemana = $_REQUEST['filtroSemana'];

                    $informesFiltrados = null;

                    foreach ($informes as $informe) {
                        if (($informe->getSemana() == $filtroSemana || $filtroSemana == 'TODAS') && ($informe->getRegion() == $filtroRegion || $filtroRegion == 'TODAS')) {
                            $informesFiltrados[] = $informe;
                        }
                    }

                    $informes = $informesFiltrados;
                }

                //Calcula los datos totales
                $infectados = 0;
                $fallecidos = 0;
                $altas = 0;

                if (is_array($informes)) {
                    for ($i = 0; $i < sizeof($informes); $i++) {
                        $infectados += $informes[$i]->getNInfectados();
                        $fallecidos += $informes[$i]->getNFallecidos();
                        $altas += $informes[$i]->getNAltas();
                    }
                } else {
                    ?>
                <div class="col-12 d-flex justify-content-center">
                    <p class="text-danger mt-4">No hay informes que coincidan con tu búsqueda.</p>
                </div>
                    <?php
                }
                ?>

                <!-- Filtro -->
                <div class="col-2 mt-4 ml-4">
                    <h5 class="h5">Filtrar por:</h5>
                </div>
                <div class="col-8 mt-4">
                    <form name="filtro" action="index.php" method="POST">
                        <div class="form-group">
                            <label for="filtroRegion">Región: </label>
                            <select class="form-control" name="filtroRegion">
                                <option value="TODAS">TODAS</option>
                                <?php
                                foreach ($regiones as $region) {
                                    ?>
                                    <option value="<?php
                                    echo $region->getNombre();
                                    ?>"<?php
                                            if (isset($filtroRegion) && $filtroRegion == $region->getNombre()) {
                                                echo 'selected';
                                            }
                                            ?>><?php echo $region->getNombre(); ?></option>
                                            <?php
                                        }
                                        ?>
                            </select>
                            <label for="filtroSemana">Semana: </label>
                            <select class="form-control" name="filtroSemana">
                                <option value="TODAS">TODAS</option>
                                <?php
                                foreach ($semanas as $semana) {
                                    ?>
                                    <option value="<?php
                                    echo $semana;
                                    ?>"<?php
                                            if (isset($filtroSemana) && $filtroSemana == $semana) {
                                                echo 'selected';
                                            }
                                            ?>><?php echo $semana; ?></option>
                                            <?php
                                        }
                                        ?>
                            </select>
                        </div>


                </div>
                <div class="col-1 mt-5">
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
                            if (is_array($informes)) {
                                for ($i = 0; $i < sizeof($informes); $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $informes[$i]->getSemana(); ?></td>
                                        <td><?php echo $informes[$i]->getRegion(); ?></td>
                                        <td>
                                            <form name="verInforme" action="controladores/controladorInformes.php" method="POST">
                                                <input type="hidden" name="id" value="<?php echo $informes[$i]->getId(); ?>">
                                                <input type="submit" class="btn btn-primary" name="verInforme" value="Ver informe">
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
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
