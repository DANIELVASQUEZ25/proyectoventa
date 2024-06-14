<?php
    require 'config/config.php';
    require 'config/database.php';
    $db = new Database();
    $con = $db->conectar();

    $id_transaccion = isset($_GET['key']) ? $_GET['key'] : '0';
    $error = '';
    if($id_transaccion == ''){
        $error = 'Error al procesar la información';
    }else{
        $sql = $con->prepare("SELECT count(id) FROM compra WHERE id_transaccion=? AND status=?");
        $sql->execute([$id_transaccion, 'COMPLETED']);

        // Busca los detalles del producto
        if($sql->fetchColumn() > 0){
            $sql = $con->prepare("SELECT id, fecha, email, total FROM compra WHERE id_transaccion=? AND status=? LIMIT 1");
            $sql->execute([$id_transaccion, 'COMPLETED']);
            $row = $sql->fetch(PDO::FETCH_ASSOC);

            $idcompra = $row['id'];
            $total = $row['total'];
            $fecha = $row['fecha'];

            $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra =?");
            $sqlDet->execute([$idcompra]);
        }else{
            $error = 'Error al comprobar la transacción';
        }
    }

?>

<!DOCTYPE html>
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

    
</body>
</html>