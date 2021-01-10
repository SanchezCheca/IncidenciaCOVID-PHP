<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Crear informe - inCOVID</title>

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
                
                //Comprueba que el usuario es autor
                if (!(isset($usuarioIniciado) && $usuarioIniciado->isAutor())) {
                    $_SESSION['mensaje'] = 'No tienes permiso para ver esa página.';
                    header('Location: ../index.php');
                }
                ?>

                <!-- Título de la sección -->
                <div class="col-12 mt-4 ml-4">
                    <h3 class="h3">Nuevo informe</h3>
                </div>

                <!-- Cuerpo -->
                <div class="col-12 mt-4 px-4 d-flex justify-content-center">
                    <form class="w-75" name="formularioNuevoInforme" action="../controladores/controladorInformes.php" method="POST">
                        <div class="form-group">
                            <label for="semana">Semana</label>
                            <select class="form-control" name="semana">
                                <?php
                                //Da la opción de crear un informe en cualquiera de las últimas 15 semanas
                                for ($i = 0; $i < 15; $i++) {
                                    $i2 = $i + 1;
                                    $semana = date("M j", strtotime("this monday - $i2 week")) . ' - ' . date("M j", strtotime("this sunday - $i week"));
                                    echo '<option value="' . $semana . '">' . $semana . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="region">Región</label>
                            <select class="form-control" name="region">
                                <?php
                                //Recupera todas las regiones del archivo auxiliar Regiones.php
                                require_once '../auxiliar/Regiones.php';
                                foreach (Regiones::$REGIONES as $region) {
                                    echo '<option value="' . $region . '">' . $region . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nInfectados">Nº de infectados</label>
                            <input class="form-control" name="nInfectados" type="number" required="">
                        </div>
                        <div class="form-group">
                            <label for="nFallecidos">Nº de fallecidos</label>
                            <input class="form-control" name="nFallecidos" type="number" required="">
                        </div>
                        <div class="form-group">
                            <label for="nAltas">Nº de altas</label>
                            <input class="form-control" name="nAltas" type="number" required="">
                        </div>
                        <input type="submit" name="crearInforme" value="Crear informe" class="btn btn-primary w-100">
                    </form>
                </div>
                <div class="col-12 d-flex justify-content-center mt-3">
                    <p><a href="../index.php">Volver</a></p>
                </div>
                <?php include '../recursos/footer.php'; ?>
            </div>

        </div>
    </body>
</html>
