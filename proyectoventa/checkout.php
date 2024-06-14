
<?php
  require 'config/config.php';
  require 'config/database.php';
  $db = new Database();
  $con = $db->conectar();

  $productos = isset($_SESSION ['carrito']['producto']) ? $_SESSION ['carrito']['producto'] : null;

  $lista_carrito = array ();
  if($productos != null){
    foreach($productos as $clave => $cantidad){
        $sql = $con-> prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id = ? AND activo = 1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    } 
  }


  //session_destroy();

?>

<!-- Estructura HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meraviglie Visive</title>

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
                <strong>Meraviglie Visive</strong>
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
                <a href="carrito.php" class="btn btn-primary">Carrito <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span></a>
                </div>

            </div>
        </div>
    </header>
    <!-- Termina el Header -->

  <!-- Comienza el Main -->
    <main>
        <!-- Inicio Container -->
        <div class="container">
            <div class="table-responsive">
            
                <!-- Inicia table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($lista_carrito == null){
                            echo '<tr><td colspan="5" class="text-center"><b>Lista Vacia</b></td></tr>';
                            } else {
                                $total = 0;
                                foreach($lista_carrito as $producto){
                                    $_id = $producto['id'];
                                    $nombre = $producto['nombre'];
                                    $precio = $producto['precio'];
                                    $descuento = $producto['descuento'];
                                    $cantidad = $producto['cantidad'];
                                    $precio_descuento = $precio - (($precio * $descuento) / 100);
                                    $subtotal = $cantidad * $precio_descuento;
                                    $total += $subtotal;
                        ?>
                                    <tr>
                                        <td> <?php echo $nombre; ?></td>
                                        <td> <?php echo MONEDA . number_format($precio_descuento, 0, '.', ','); ?></td>
                                        
                                        <td> 
                                            <input type="number" min = "1" max ="10" step="1" value="<?php echo $cantidad; ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizarCantidad(this.value, <?php echo $_id; ?>)">
                                        </td>
                                        
                                        <td> 
                                            <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 0, '.', ','); ?></div>
                                        </td>

                                        <td> <a id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a></td>
                                    </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">
                                <p class="h3" id ="total"><?php echo MONEDA . number_format($total, 0, '.', ','); ?></p>
                            </td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
                <!-- Finaliza Table -->
            </div>

            <?php if($lista_carrito != null){ ?>
                <div class="row">
                    <div class="col-md-5 offset-md-7 d-grid gap-2">
                        <a class="btn btn-primary btn-lg" href="pago.php">Realizar Pago</a>
                    </div>
                </div>
            <?php } ?>


        </div>
        <!-- Finaliza Container -->

    </main>
    <!-- Termina el Main -->

    <!-- Modal -->
    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eliminaModalLabel">Alerta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    ¿Eliminar el producto? 
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()"> Eliminar </button>
                </div>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        let eliminaModal = document.getElementById('eliminaModal')
        eliminaModal.addEventListener('show.bs.modal', function(event){
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
            buttonElimina.value = id
        })

        function actualizarCantidad(cantidad, id){
            let url = "clases/actualizar_carrito.php";
            let formData = new FormData();
            formData.append("action", "agregar");
            formData.append("id", id);
            formData.append("cantidad", cantidad);

            fetch(url, {
                method: "POST",
                body: formData,
                mode: "cors"
            }).then(response => response.json())
            .then(data =>{
                if(data.ok){
                    let divsubtotal = document.getElementById('subtotal_' + id);
                    divsubtotal.innerHTML = data.sub;

                    let total = 0.00
                    let list = document.getElementsByName("subtotal[]");

                    for(let i = 0; i < list.length; i++ ){
                        total += parseFloat(list[i].innerHTML.replace(/[$,]/g, ""));
                    }

                    total = new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 0
                    }).format(total)
                    document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total
                }
            })
        }

        function eliminar(){

            let botonElimina = document.getElementById('btn-elimina')
            let id = botonElimina.value

            let url = "clases/actualizar_carrito.php";
            let formData = new FormData();
            formData.append('action', 'eliminar');
            formData.append("id", id);

            fetch(url, {
                method: "POST",
                body: formData,
                mode: "cors"
            }).then(response => response.json())
            .then(data =>{
                if(data.ok){
                    location.reload()
                }
            })
        }
    </script>
</body>
</html>