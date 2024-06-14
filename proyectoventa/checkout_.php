<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paypal</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AYOebaP4U5JwBd7iYyNRr6kiBSjO5aPEKXFcE25FSNyKGiqiQQRlD5j8R5qJz0aPJvWxCxe6inWhhSdH&currency=MXN"></script>
</head>
<body>
    <div id="paypal-button-container"></div>

    <a href="pagocompletado.html">Pincha aquí</a>
    
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
                            value: 3000
                        }
                    }]
                });
            },
        
            //Cuando se apruebe el pago
            onApprove: function(data, actions){
                actions.order.capture().then(function(detalles){
                    window.location.href="pagocompletado.html";
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