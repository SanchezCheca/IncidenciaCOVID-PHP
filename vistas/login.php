<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Iniciar sesión - inCOVID</title>

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

                <?php include '../recursos/cabecera.php'; ?>

                <!-- Título de la sección -->
                <div class="col-12 mt-4 ml-4">
                    <h3 class="h3">Inicia sesión</h3>
                </div>

                <!-- Cuerpo -->
                <div class="col-12 mt-4 px-4 d-flex justify-content-center">
                    <form class="w-75" name="formularioInicioSesion" action="../controladores/controladorPrincipal.php" method="POST">
                        <div class="form-group">
                            <input type="email" name="correo" class="form-control" aria-describedby="emailHelp" placeholder="Correo electrónico">
                        </div>
                        <div class="form-group">
                            <input type="password" name="pass" class="form-control" placeholder="Contraseña">
                        </div>
                        <input type="submit" name="inicioSesion" value="Iniciar sesión" class="btn btn-primary">
                    </form>
                </div>
                <div class="col-12 mt-4 d-flex justify-content-center">
                    <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
                </div>
                <?php include '../recursos/footer.php'; ?>
            </div>

        </div>
    </body>
</html>
