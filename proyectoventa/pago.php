
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
  }else{
    header("location: index.php");
    exit;
  }


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

            <div class="row">

                <div class="col-6">
                    <h4>Detalles de Pago</h4>

                    <!-- Hacer el pago por Paypal -->
                    <div id="paypal-button-container"></div>

                </div>

                <div class="col-6">
                    <div class="table-responsive">
            
                        <!-- Inicia table -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
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
                                                
                                                <td> 
                                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 0, '.', ','); ?></div>
                                                </td>
                                            </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2">
                                        <p class="h3 text-end" id ="total"><?php echo MONEDA . number_format($total, 0, '.', ','); ?></p>
                                    </td>
                                </tr>
                            </tbody>
                            <?php } ?>
                        </table>
                        <!-- Finaliza Table -->
                    </div>
                </div>
            
            </div>
        </div>
        <!-- Finaliza Container -->

    </main>
    <!-- Termina el Main -->

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Link de Paypal -->
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>"></script>

    <!-- Script de Paypal  -->
    <script>
        paypal.Buttons({
            style:{
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },

            //Creamos el pedido
            createOrder: function(data, actions){
                return actions.order.create({
                    purchase_units: [{
                        amount:{
                            value: <?php echo $total; ?>,

                        }
                    }]
                });
            },
        
            //Cuando se apruebe el pago
            onApprove: function(data, actions){
                let url = "clases/captura.php"
                actions.order.capture().then(function(detalles){
                    console.log(detalles);
                    let url = "clases/captura.php"

                    return fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            detalles: detalles
                        })
                    }).then(function(response){
                        window.location.href = 'pagocompletado.php?key=' + detalles['id'];
                    })
                });
            },

           //Cuando se cancela el pago
            onCancel:function(data){
                alert("Pago cancelado");
                console.log(data)
            }
        }).render('#paypal-button-container');
    </script>
    
</body>
</html>