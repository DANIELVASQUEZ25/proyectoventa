
<?php
  require 'config/config.php';
  require 'config/database.php';
  $db = new Database();


  //session_destroy();

  print_r($_SESSION);
?>

<!-- Estructura HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DaniBytes Technologies</title>

    <!--  Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

    <!-- Comienza el Header -->
    <header>
        <div class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm"> 
        <div class="container">
            <a href="#" class="navbar-brand d-flex align-items-center">
            <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="me-2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg> -->
            <strong>DaniBytes Technologies</strong>
            </a>

            <!-- Boton Hamburguesa -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Categorias -->
            <div class="collapse navbar-collapse" id="navbarHeader">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                <a href="#" class="nav-link active">Catálogo</a>
                </li>
                
                <li class="nav-item">
                <a href="#" class="nav-link">Contacto</a>
                </li>

            </ul>
            <a href="checkout.php" class="btn btn-primary">Carrito <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span></a>
            </div>

        </div>
        </div>
    </header>
    <!-- Termina el Header -->

    <!-- Inicio de las card de Productos -->
    <main>
        <div class="container">

        <!-- Inicia El formulario para el cliente -->
            <h2>Datos del cliente</h2>
            <form action="registro.php" class="row g-3" method="post" autocomplete="off">

                <div class="col-md-6">
                    <label for="nombres"><span class="text-danger">*</span> Nombres</label>
                    <input type="text" class="form-control" name="nombres" id="nombres" required>
                </div>

                <div class="col-md-6">
                    <label for="apellidos"><span class="text-danger">*</span> Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" id="apellidos" required>
                </div>

                <div class="col-md-6">
                    <label for="email"><span class="text-danger">*</span> Correo electrónico</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>

                <div class="col-md-6">
                    <label for="telefono"><span class="text-danger">*</span> Teléfono</label>
                    <input type="tel" class="form-control" name="telefono" id="telefono" required>
                </div>

                <div class="col-md-6">
                    <label for="identificacion"><span class="text-danger">*</span> Identificacion</label>
                    <input type="text" class="form-control" name="identificacion" id="identificacion" required>
                </div>

                <div class="col-md-6">
                    <label for="usuario"><span class="text-danger">*</span> Correo electrónico</label>
                    <input type="text" class="form-control" name="usuario" id="usuario" required>
                </div>

                <div class="col-md-6">
                    <label for="password"><span class="text-danger">*</span> Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>

                <div class="col-md-6">
                    <label for="repassword"><span class="text-danger">*</span> Repetir Contraseña</label>
                    <input type="password" class="form-control" name="repassword" id="repassword" required>
                </div>

                <i><b>Nota: </b>Los campos con asteriscos son obligatorios</i>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit"> Registrar</button>
                </div>

            </form>
            <!-- Finaliza El formulario para el cliente -->

        </div>
    </main>


  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  
</body>
</html>