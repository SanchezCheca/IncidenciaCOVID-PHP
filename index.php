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
            <div class="row principal">
                
                <?php include 'recursos/cabecera.php'; ?>

                <!-- Título de la sección -->
                <div class="col-12 mt-4 ml-4">
                    <h3 class="h3">Últimos informes de incidencia</h3>
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
                            <tr>
                                <th scope="row">1</th>
                                <td>Castilla-La Mancha</td>
                                <td><button class="btn btn-primary">Ver informe</button></td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Madrid</td>
                                <td><button class="btn btn-primary">Ver informe</button></td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Barcelona</td>
                                <td><button class="btn btn-primary">Ver informe</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>


    </body>
</html>
