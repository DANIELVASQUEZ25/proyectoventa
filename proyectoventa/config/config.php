<?php
    define("CLIENT_ID", "AYOebaP4U5JwBd7iYyNRr6kiBSjO5aPEKXFcE25FSNyKGiqiQQRlD5j8R5qJz0aPJvWxCxe6inWhhSdH");
    define("CURRENCY", "MXN");
    define("KEY_TOKEN", "dan.DAN-321*!");
    define("MONEDA", "$");

    // Para que inicie sesion cuando cada usuario entre al portal
    session_start();

    //Para que el carrito vaya aumentando
    $num_cart = 0;
    if(isset($_SESSION['carrito']['producto'])){
        $num_cart = count($_SESSION['carrito']['producto']);
    }
?>