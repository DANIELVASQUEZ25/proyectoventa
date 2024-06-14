
<?php
  require 'config/config.php';
  require 'config/database.php';
  $db = new Database();
  $con = $db->conectar();
  $sql = $con->  prepare("SELECT id, nombre, precio FROM productos WHERE activo = 1");
  $sql->execute();
  $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

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
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

      <!-- Crear las Card -->
      <?php 
        foreach ($resultado as $row){ ?>
          <div class="col">
            <div class="card shadow-sm">

              <?php
                $id = $row['id'];
                $imagen = "img/productos/" .$id."/vista.jpg";
                if(!file_exists($imagen)){
                  $imagen = "img/no-foto.webp";
                }
              ?>
              
              <img src= "<?php echo $imagen; ?>">              
              <div class="card-body">
                <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                
                <!-- Para poner el separador de miles -->
                <p class="card-text">$<?php echo number_format($row['precio'], 0, ",", "."); ?></p>

                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group">

                    <!-- hash_hmac filtra información mediante una contraseña -->
                    <a href="detalles.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha256', $row['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                  
                  </div>
                  <button class="btn btn-outline-success" type="button" onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha256', $row['id'], KEY_TOKEN); ?>')">Agregar al Carrito</button>
                </div>
              </div>
            </div>
          </div>
      <?php } ?>
      <!-- Finalización de las Card -->

        </div>
    </div>
  </main>


  

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
    function addProducto(id, token){
      let url = "clases/carrito.php";
      let formData = new FormData();
      formData.append("id", id);
      formData.append("token", token);

      fetch(url, {
        method: "POST",
        body: formData,
        mode: "cors"
       }).then(response => response.json())
      .then(data =>{
        if(data.ok){
          let elemento =document.getElementById('num_cart');
          elemento.innerHTML = data.numero;
          }
      })
    }
  </script>
</body>
</html>