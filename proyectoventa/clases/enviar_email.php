<?php
use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};
//Rectificar porque no funciona


require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                    //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'telosaco59@gmail.com';                     //SMTP username
    $mail->Password   = 'semeolvido2003';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('telosaco59@gmail.com', 'Tienda Anonimato');
    $mail->addAddress('dandresvasquez@unicesar.edu.co', 'Daniel Vásquez');     //Add a recipient
    //$mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Detalles de su compra';

    $cuerpo = '<h4>Gracias por su Compra</h4>';
    $cuerpo .= '<p>El id de su compra es <b>' . $id_transaccion . '</b></p>';

    $mail->Body = utf8_decode($cuerpo);
    $mail->AltBody = 'Le enviamos los detalles de su compra';

    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');
    $mail->send();
    // echo 'Message has been sent';
} catch (Exception $e) {
    echo "Error al enviar el correo. Revisar. Mailer Error: {$mail->ErrorInfo}";
    exit;
}