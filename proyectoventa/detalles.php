<?php
  require 'config/config.php';
  require 'config/database.php';
  $db = new Database();
  $con = $db->conectar();

  $id= isset($_GET['id']) ? $_GET['id'] : '';
  $token= isset($_GET['token']) ? $_GET['token'] : '';

  //Por si quieren alterar o quitar el token 
  if($id == '' || $token ==''){
    echo "Error al realizar la petición";
    exit;
   } else {
    $token_tmp = hash_hmac('sha256', $id, KEY_TOKEN);

    if($token == $token_tmp){
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id =? AND activo = 1");
        $sql->execute([$id]);

        // Busca los detalles del producto
        if($sql->fetchColumn() > 0){
            $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_descuento = $precio -(($precio * $descuento) /100);
            $dir_images = "img/productos/$id/";

            // Buscar la imagen del producto
            $rutaimg = $dir_images . "vista.jpg";
            if(!file_exists($rutaimg)){
                $rutaimg = "img/no-foto.webp";
            }

            $imagenes = array();
            if(file_exists($dir_images)){
              $dir = dir($dir_images);
              while(($archivo = $dir->read()) != false){
                  if($archivo != "vista.jpg" && (strpos($archivo, "jpg") || strpos($archivo, "jpeg"))){
                      $imagenes [] = $dir_images . $archivo;
                  }
              }
              $dir->close();
            }

            $sqlCaracter = $con->prepare("SELECT DISTINCT(det.id_caracteristica) AS idCat, cat.caracteristica FROM det_prod_caracter AS det INNER JOIN caracteristicas AS cat ON det.id_caracteristica = cat.id WHERE id_producto =?");
            $sqlCaracter->execute([$id]);

        }
    } else {
        echo "Error al realizar la petición";
        exit;
    }
  }
?>

<!-- Estructura HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles</title>

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
        <div class="row">

            <!-- Inicia Imagen del producto -->
            <div class="col-md-6 order-md-1">

                <!-- Inicio del Carrusel -->
                <div id="carruselImages" class="carousel slide">

                  <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carruselImages" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carruselImages" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carruselImages" data-bs-slide-to="2" aria-label="Slide 3"></button>
                  </div>

                  <div class="carousel-inner">

                    <div class="carousel-item active">
                      <img src="<?php echo $rutaimg;  ?>" class="d-block w-100" alt="...">
                    </div>

                    <!-- Inicia el foreach -->
                    <?php foreach($imagenes as $img){ ?>
                    <div class="carousel-item">
                      <img src="<?php echo $img;  ?>" class="d-block w-100" alt="...">
                    </div>
                    <?php } ?>
                    <!-- Termina el foreach -->
                  </div>

                  <button class="carousel-control-prev" type="button" data-bs-target="#carruselImages" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden"> < </span>
                  </button>

                  <button class="carousel-control-next" type="button" data-bs-target="#carruselImages" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden"> > </span>
                  </button>

                </div>
                <!-- Finaliza el Carrusel -->
                
            </div>
            <!-- Finaliza ver Imagen del Producto -->

            <!-- Inicia Mostrar las descripciones del producto -->
            <div class="col-md-6 order-md-2">
                <h2> <?php echo $nombre; ?> </h2>

                <!-- Inicia PHP -->
                <?php if($descuento > 0){ ?>

                  <!-- Mostrar Precio Normal -->
                  <p><del><?php echo MONEDA . number_format($precio, 0, '.', ','); ?></del></p>
                  
                  <!-- Mostrar Precio con Descuento -->
                  <h2> <?php echo MONEDA . number_format($precio_descuento, 0, '.', ','); ?>
                  <small class="text-success"><?php echo $descuento; ?> % descuento</small>
                  </h2>

                  <!-- Si no tiene descuento lo lee normal -->
                <?php } else {?>
                  <h2><?php echo MONEDA . number_format($precio, 0, '.', ','); ?></h2>
                <?php }?>
                <!-- Finaliza PHP -->

                <p class="lead"><?php echo $descripcion; ?></p>

                <!-- Muestra los colores de los productos -->
                <div class="col-3 my-3">
                  <?php
                    while($row_cat = $sqlCaracter->fetch(PDO::FETCH_ASSOC)){
                      $idCat = $row_cat['idCat'];
                      echo $row_cat['caracteristica']. ": ";

                      echo "<select class ='form-select'> id ='cat_$idCat'";

                      $sqlDet= $con->prepare("SELECT id, valor, stock FROM det_prod_caracter WHERE id_producto =? AND id_caracteristica = ?");
                      $sqlDet->execute([$id, $idCat]);
                      while($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)){
                        echo "<option id='" . $row_det['id'] . "'>" . $row_det['valor'] . "</option>";
                      }  
                      echo "/select>";
                    }
                  ?>  
                </div>
                <!-- Finaliza la muestra de colores de los productos -->

                <div class="col-3 my-3">
                  <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" max="10" value="1">
                </div>
                
                <!-- Botones de Comprar Ahora y agregar al carrito -->
                <div class="d-grid gap-3 col-10 mx-auto">
                    <button class="btn btn-primary" type="button">Comprar Ahora</button>
                    <button id="btnAgregar" class="btn btn-outline-primary" type="button" onclick="addProducto(<?php echo $id; ?>, cantidad.value, '<?php echo $token_tmp; ?>')">Agregar al Carrito</button>
                </div>

            </div>
            <!-- Finaliza Mostrar las descripciones del producto -->
        </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>

    // let btnAgregar = document.getElementById('btnAgregar');
    // let inputCantidad = document.getElementById('cantidad').value   
    // btnAgregar.onclick =addProducto

    function addProducto(id, cantidad, token){
      let url = "clases/carrito.php";
      let formData = new FormData();
      formData.append("id", id);
      formData.append("cantidad", cantidad);
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